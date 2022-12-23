<?php

namespace Processors\Player;

use Consts\ErrorCode;
use Consts\Sessions;
use Games\Consts\PlayerValue;
use Games\Consts\UpgradeValue;
use Games\Exceptions\PlayerException;
use Games\Exceptions\UserException;
use Games\Players\PlayerHandler;
use Games\Players\UpgradeItemsHandler;
use Games\Players\UpgradeUtility;
use Games\Pools\RankUpItemsPool;
use Games\Users\UserBagHandler;
use Games\Users\UserHandler;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\BaseProcessor;

class RankUpPage extends BaseProcessor{

    public function Process(): ResultData
    {
        $playerID = InputHelper::post('playerID');
        $userID = $_SESSION[Sessions::UserID];
        $userHandler = new UserHandler($userID);
        $userInfo = $userHandler->GetInfo();
        $userBagHandler = new UserBagHandler($userID);

        if(!in_array($playerID,$userInfo->players))
        throw new UserException(UserException::NotHoldPlayer,['player' => $playerID]);

        $playerInfo = (new PlayerHandler($playerID))->GetInfo();

        if($playerInfo->rank == PlayerValue::RankMax)
        throw new PlayerException(PlayerException::AlreadyRankMax,['playerID' => $playerID]);

        $results = new ResultData(ErrorCode::Success);      
        $upgradeHandler = new UpgradeItemsHandler($playerInfo->rank + 1, $playerInfo->ele, RankUpItemsPool::Instance());
        $itemRequires = $upgradeHandler->GetUpgradeItems();

        $chargeRequire = $upgradeHandler->GetCoinCost();
        $itemHolds = [];
        $isEnough = true;
        foreach($itemRequires as $itemID => $amount)
        {
            $hold = $userBagHandler->GetItemAmount($itemID);
            $itemHolds[$itemID] = $hold;
            if($hold < $amount) $isEnough = false;
        }

        $results->currentLevel = $playerInfo->level;
        $results->currentRank = 
        [
            'rank' => $playerInfo->rank,
            'maxLevel' => PlayerValue::RankMaxLevel[$playerInfo->rank],
            'skillLevelMax' => UpgradeValue::SkillLevelLimit[$playerInfo->rank],
        ];
        $results->nextRank = 
        [
            'rank' => $playerInfo->rank + UpgradeValue::RankUnit,
            'maxLevel' => PlayerValue::RankMaxLevel[$playerInfo->rank + UpgradeValue::RankUnit],
            'skillLevelMax' => UpgradeValue::SkillLevelLimit[$playerInfo->rank + UpgradeValue::RankUnit],
        ];
        $results->itemInfos = [];
        foreach($itemRequires as $itemID => $amount)
        {
            $results->itemInfos[] = UpgradeUtility::GetUpgradeItemInfo($itemID,$itemHolds[$itemID],$amount);
        }
        
        $results->requireCoin = 
        [
            'requireAmount' => $chargeRequire,
            'isEnough' => $userInfo->coin >= $chargeRequire,
        ];
        

        $results->canRankUp = (PlayerValue::RankMaxLevel[$playerInfo->rank] == $playerInfo->level
                    && $isEnough && $userInfo->coin >= $chargeRequire);
                        
        return $results;
    }
}