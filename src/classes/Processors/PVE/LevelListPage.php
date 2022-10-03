<?php

namespace Processors\PVE;

use Consts\ErrorCode;
use Consts\Sessions;
use Games\Consts\PVEValue;
use Games\Exceptions\PVEException;
use Games\Players\PlayerHandler;
use Games\PVE\PVEChapterData;
use Games\PVE\PVELevelHandler;
use Games\PVE\PVEUtility;
use Games\PVE\UserPVEHandler;
use Games\Scenes\SceneHandler;
use Games\Skills\SkillHandler;
use Games\Users\RewardHandler;
use Games\Users\UserHandler;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\BaseProcessor;
use stdClass;

class LevelListPage extends BaseProcessor
{
    public function Process(): ResultData
    {
        $chapterID = InputHelper::post('chapterID');
        $userID = $_SESSION[Sessions::UserID];

        $chapterInfo = PVEChapterData::GetChapterInfo($chapterID);
        $userPVEHandler = new UserPVEHandler($userID);
        $userPVEInfo = $userPVEHandler->GetInfo();
        $result = new ResultData(ErrorCode::Success);

        //章節未解鎖
        if(!isset($userPVEInfo->clearLevelInfo[$chapterID]))
        throw new PVEException(PVEException::ChapterLock,['ChapterID' => $chapterID]);

        $levels = [];
        //各等階獎牌數量。加上玩家目前此章節獎牌數量。
        foreach($chapterInfo->levels as $levelID)
        {
            $levelInfo = (new PVELevelHandler($levelID))->GetInfo();
            //是否有通關過。沒通關過不會有資料
            $medalAount = $userPVEHandler->HasClearedLevel($chapterID,$levelInfo->levelID) ?
                          $userPVEInfo->clearLevelInfo[$chapterID][$levelID] :
                          0;
            //是否已通過前置關卡                                
            $isUnlock = true;
            if(!empty($levelInfo->preLevels))
            {
                foreach($levelInfo->preLevels as $preLevel)
                if(!$userPVEHandler->HasClearedLevel($chapterID,$preLevel))$isUnlock = false;
            }
            
            $firstRewards = PVEUtility::GetItemsInfoByRewardHandler(new RewardHandler($levelInfo->firstRewardID));
            $sustainRewards = PVEUtility::GetItemsInfoByRewardHandler(new RewardHandler($levelInfo->sustainRewardID));

            $sceneHandler = new SceneHandler($levelInfo->sceneID);            
            $climate = $sceneHandler->GetClimate();
            $canRush = PVEValue::LevelMedalMax == $medalAount;//且vip等級大於1
            //TODO：要判斷VIP是否可以快速掃蕩
            $levels[] = 
            [
                'id' => $levelInfo->levelID,
                'name' => $levelInfo->levelName,
                'description' => $levelInfo->description,
                'recommendLevel' => $levelInfo->recommendLevel,
                'currentMedalAmount' => $medalAount,
                'scene' => $levelInfo->sceneID,
                'sceneName' => $sceneHandler->GetInfo()->name,
                'enviroment' => $sceneHandler->GetInfo()->env,
                'weather' => $climate->weather,
                'windDirection' => $climate->windDirection,
                'windSpeed' => $climate->windSpeed,
                'lighting' => $climate->lighting,
                'isUnlock' => $isUnlock,
                'powerRequired' => $levelInfo->power,
                'hasCleared' => $userPVEHandler->HasClearedLevel($chapterID,$levelID),
                'firstReward' => $firstRewards,
                'sustainRewards' => $sustainRewards,
                'canRush' => $canRush,
            ];
            
        }
        $userHandler = new UserHandler($userID);        
        $playerHandler = new PlayerHandler($userHandler->GetInfo()->player);
        $playerInfo = $playerHandler->GetInfo();
        $player = new stdClass();
        $player->level = $playerInfo->level;
        $player->id = $playerInfo->id;
        $player->idName = $playerInfo->idName;
        $player->name = $playerInfo->name;

        
        $player->skills = [];
        foreach($playerInfo->skills as $skill){
            
            $handler = new SkillHandler($skill->id);
            $handler->playerHandler = $playerHandler;
            $info = $handler->GetInfo();
            $player->skills[] = [
                'id' => $info->id,
                'name' => $info->name,
                'icon' => $info->icon,
                'description' => $info->description,
                'level' => $skill->level,
                'slot' => $skill->slot,
                'energy' => $info->energy,
                'cooldown' => $info->cooldown,
                'duration' => $info->duration,
                'ranks' => $info->ranks,
                'maxDescription' => $info->maxDescription,
                'maxCondition' => $info->maxCondition,
                'maxConditionValue' => $info->maxConditionValue,
                'attackedDesc' => $info->attackedDesc,
                'effects' => $handler->GetEffects(),
                // 'maxEffects' => $handler->GetMaxEffects()
            ];
        }
        $player->skillHole = $playerInfo->skillHole;
        $userHandler->HandlePower(0);

        $userMedalAmount = array_sum($userPVEInfo->clearLevelInfo[$chapterID]);
        $result->name = $chapterInfo->name;
        $result->chapterMedal = $userMedalAmount;
        $result->currentPower = $userHandler->GetInfo()->power;
        $result->medalAmountFirst = $chapterInfo->medalAmountFirst;
        $result->rewardFirst = PVEUtility::GetItemsInfoByRewardHandler(new RewardHandler($chapterInfo->rewardIDFirst));
        $result->medalAmountSecond = $chapterInfo->medalAmountSecond;
        $result->rewardSecond = PVEUtility::GetItemsInfoByRewardHandler(new RewardHandler($chapterInfo->rewardIDSecond));
        $result->medalAmountThird = $chapterInfo->medalAmountThird;
        $result->rewardIDThrid = PVEUtility::GetItemsInfoByRewardHandler(new RewardHandler($chapterInfo->rewardIDThrid));
        $result->levels = $levels;
        $result->player = $player;

        
        return $result;
    }
}