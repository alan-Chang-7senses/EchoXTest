<?php

namespace Processors\Player;


use Consts\ErrorCode;
use Consts\Sessions;
use Games\Consts\UpgradeValue;
use Games\Exceptions\UserException;
use Games\Players\PlayerHandler;
use Games\Players\UpgradeUtility;
use Games\Skills\SkillHandler;
use Games\Users\UserBagHandler;
use Games\Users\UserHandler;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\BaseProcessor;

class UpgradeSkillPage extends BaseProcessor{

    public function Process(): ResultData
    {
        $playerID = InputHelper::post('playerID');
        $userID = $_SESSION[Sessions::UserID];
        $userHandler = new UserHandler($userID);
        $userInfo = $userHandler->GetInfo();
        $userBagHandler = new UserBagHandler($userID);
        
        if(!in_array($playerID,$userInfo->players))
        throw new UserException(UserException::NotHoldPlayer,['player' => $playerID]);

        $playerhandler = new PlayerHandler($playerID);
        $playerInfo = $playerhandler->GetInfo();
        
        $results = new ResultData(ErrorCode::Success);
        $skillDatas = [];        
        $levelLimit = UpgradeValue::SkillLevelLimit[$playerInfo->rank];
        foreach($playerInfo->skills as $skill)
        {
            $skillHandler = new SkillHandler($skill->id);
            $skillInfo = $skillHandler->GetInfo();
            $skillLevel = $playerhandler->SkillLevel($skillInfo->id);
            $skillHandler->playerHandler = $playerhandler;
            $hasReachedRankMax = $skillLevel >= $levelLimit;
            $requireInfo = $hasReachedRankMax ? null : UpgradeUtility::GetSkillUpgradeRequire($skillLevel,$skill->id,$playerID);
            $requireItemIDs = $requireInfo->items ?? [];
            $itemInfos = [];
            $isEnough = false;
            if(!empty($requireItemIDs))
            {
                $isEnough = true;
                foreach($requireItemIDs as $itemID => $amount)
                {
                    $holdAmount = $userBagHandler->GetItemAmount($itemID);
                    $itemInfos[] = UpgradeUtility::GetUpgradeItemInfo($itemID,$holdAmount,$amount);
                    if($holdAmount < $amount)$isEnough = false;         
                }
            }
            $skillDatas[] = 
            [
                'id' => $skillInfo->id,
                'hasReachedLimit' => $hasReachedRankMax,
                'requireCoin' => $hasReachedRankMax ? null : $requireInfo->coin,
                'isCoinEnough' =>$hasReachedRankMax ? null : $requireInfo->coin <= $userInfo->coin,
                'itemInfos' => $hasReachedRankMax ? null : $itemInfos,
                'isRequireItemEnough' =>$hasReachedRankMax ? null : $isEnough,
                'name' => $skillInfo->name,
                'icon' => $skillInfo->icon,
                'description' => $skillInfo->description,
                'level' => $skill->level,
                'slot' => $skill->slot,
                'energy' => $skillInfo->energy,
                'cooldown' => $skillInfo->cooldown,
                'duration' => $skillInfo->duration,
                'ranks' => $skillInfo->ranks,
                'maxDescription' => $skillInfo->maxDescription,
                'maxCondition' => $skillInfo->maxCondition,
                'maxConditionValue' => $skillInfo->maxConditionValue,
                'attackedDesc' => $skillInfo->attackedDesc,
                'effects' => $skillHandler->GetEffects(),
                'maxEffects' => $skillHandler->GetMaxEffects()
            ];
        }
        $results->skillsData = $skillDatas;
        return $results;
    }
}