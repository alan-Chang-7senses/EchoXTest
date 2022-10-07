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

        $userHandler = new UserHandler($userID);
        $userHandler->HandlePower(0);

        //章節未解鎖
        if(!isset($userPVEInfo->clearLevelInfo[$chapterID]))
        throw new PVEException(PVEException::ChapterLock,['ChapterID' => $chapterID]);

        $levels = [];
        //各等階獎牌數量。加上玩家目前此章節獎牌數量。
        foreach($chapterInfo->levels as $levelID)
        {
            $levelInfo = (new PVELevelHandler($levelID))->GetInfo();
            //是否有通關過。
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
            $botInfo = [];
            foreach($levelInfo->aiInfo as $aiID => $trackNumber)
            {
                $botInfo[] = 
                [
                    'aiUserID' => $aiID,
                    'trackNumber' => $trackNumber,
                ];
            }
            
            $firstRewards = PVEUtility::GetItemInfos($levelInfo->firstRewardItemIDs);
            $sustainRewards = PVEUtility::GetItemInfos($levelInfo->sustainRewardItemIDs);

            $sceneHandler = new SceneHandler($levelInfo->sceneID);            
            $climate = $sceneHandler->GetClimate();
            $canRush = PVEValue::LevelMedalMax == $medalAount;//且vip等級大於1
            //VIP系統暫時不做。先判斷獎牌數量即可
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
                'botInfo' => $botInfo,
                'isUnlock' => $isUnlock,
                'powerRequired' => $levelInfo->power,
                'hasCleared' => $userPVEHandler->HasClearedLevel($chapterID,$levelID),
                'firstReward' => $firstRewards,
                'sustainReward' => $sustainRewards,
                'canRush' => $canRush,
            ];
            
        }
            
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
        

        $userMedalAmount = array_sum($userPVEInfo->clearLevelInfo[$chapterID]);
        $result->name = $chapterInfo->name;
        $result->chapterMedal = $userMedalAmount;
        $result->currentPower = $userHandler->GetInfo()->power;
        $result->medalAmountFirst = $chapterInfo->medalAmountFirst;
        $result->rewardFirst = PVEUtility::GetItemsInfoByRewardHandler(new RewardHandler($chapterInfo->rewardIDFirst));
        $result->medalAmountSecond = $chapterInfo->medalAmountSecond;
        $result->rewardSecond = PVEUtility::GetItemsInfoByRewardHandler(new RewardHandler($chapterInfo->rewardIDSecond));
        $result->medalAmountThird = $chapterInfo->medalAmountThird;
        $result->rewardThrid = PVEUtility::GetItemsInfoByRewardHandler(new RewardHandler($chapterInfo->rewardIDThrid));
        $result->levels = $levels;
        $result->player = $player;

        
        return $result;
    }
}