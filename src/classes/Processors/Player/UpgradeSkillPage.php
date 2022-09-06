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
            $hasReachedRankMax = $skillLevel >= $levelLimit;
            $chipID = $hasReachedRankMax ? null : UpgradeUtility::GetSkillUpgradeChipID($skillInfo->id);            
            $requireItems = $hasReachedRankMax ? null : UpgradeUtility::GetSkillUpgradeRequireItems($skillLevel,$chipID);            
            $requireItemsHoldAmount = [];
            $isEnough = false;
            if($requireItems !== null)
            {
                $isEnough = true;
                foreach($requireItems as $itemID => $amount)
                {
                    $holdAmount = $userBagHandler->GetItemAmount($itemID);
                    $requireItemsHoldAmount[$itemID] = $holdAmount;
                    if($holdAmount < $amount)$isEnough = false;         
                }
            }
            $skillDatas[] = 
            [
                'id' => $skillInfo->id,
                'hasReachedLimit' => $hasReachedRankMax,
                'requireCoin' => $hasReachedRankMax ? null : UpgradeValue::SkillUpgradeCharge[$skillLevel],
                'isCoinEnough' =>$hasReachedRankMax ? null : UpgradeValue::SkillUpgradeCharge[$skillLevel] <= $userInfo->coin,
                'requireItem' => $requireItems,
                'itemHold' => $requireItemsHoldAmount,
                'isRequireItemEnough' =>$hasReachedRankMax ? null : $isEnough,

            ];
        }
        $results->skillsData = $skillDatas;
        return $results;
    }
}