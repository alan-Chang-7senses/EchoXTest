<?php

namespace Processors\Player;

use Consts\ErrorCode;
use Consts\Sessions;
use Games\Accessors\GameLogAccessor;
use Games\Consts\ItemValue;
use Games\Consts\PlayerValue;
use Games\Consts\UpgradeValue;
use Games\Exceptions\ItemException;
use Games\Exceptions\PlayerException;
use Games\Exceptions\UserException;
use Games\Players\PlayerHandler;
use Games\Users\UserBagHandler;
use Games\Users\UserHandler;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\BaseProcessor;
use stdClass;

class RankUp extends BaseProcessor{

    public function Process(): ResultData
    {
        $playerID = InputHelper::post('playerID');
        $userID = $_SESSION[Sessions::UserID];
        $userHandler = new UserHandler($userID);
        $userInfo = $userHandler->GetInfo();
        $userBagHandler = new UserBagHandler($userID);
        
        if(!in_array($playerID,$userInfo->players))
        throw new UserException(UserException::NotHoldPlayer,['player' => $playerID]);
        
        $playerhandler =new PlayerHandler($playerID); 
        $playerInfo = $playerhandler->GetInfo();
        $searchItems = [];

        if($playerInfo->rank == PlayerValue::RankMax)
        throw new PlayerException(PlayerException::AlreadyRankMax,['playerID' => $playerID]);
        
        if(PlayerValue::RankMaxLevel[$playerInfo->rank] != $playerInfo->level)
        throw new PlayerException(PlayerException::NotReachMaxLevelYet,['playerID' => $playerID]);

        $dustItemID = UpgradeValue::RankUpItem[$playerInfo->ele][UpgradeValue::Dust];
        $crystalItemID = UpgradeValue::RankUpItem[$playerInfo->ele][UpgradeValue::Crystal];

        
        $amountInfo = UpgradeValue::RankUpItemAmount[$playerInfo->rank];
        $dustAmount = $amountInfo['dustAmount'];
        $crystalAmount = $amountInfo['crystalAmount'];
        $charge = $amountInfo['charge'];

        
        if($userInfo->coin < $charge)
        throw new UserException(UserException::UserCoinNotEnough,['userID' => $userID]);

        $searchItems = 
        [
            $dustItemID => $dustAmount,
            $crystalItemID => $crystalAmount,
        ];

        //檢驗數量是否足夠
        foreach($searchItems as $itemID => $amount)
        {
            if($amount <= 0)continue;
            if($userBagHandler->GetItemAmount($itemID) < $amount)
            throw new ItemException(ItemException::ItemNotEnough,['item' => $itemID]);
        }
        
        $playerhandler->SaveLevel(['rank' => $playerInfo->rank + UpgradeValue::RankUnit]);
        $userHandler->SaveData(['coin' => $userInfo->coin - $charge]);
        
        // 扣道具
        if($dustAmount > 0)
        {
            $itemTemp = new stdClass();
            $itemTemp->ItemID = $dustItemID;
            $itemTemp->Amount = $dustAmount;
            $userBagHandler->DecItems($itemTemp,ItemValue::CauseRankUp);
        }
        if($crystalAmount > 0)
        {
            $itemTemp = new stdClass();
            $itemTemp->ItemID = $crystalItemID;
            $itemTemp->Amount = $crystalAmount;
            $userBagHandler->DecItems($itemTemp,ItemValue::CauseRankUp);
        }
        (new GameLogAccessor())->AddUpgradeLog($playerID,null,null,-$charge,null,null,UpgradeValue::RankUnit);
        $results = new ResultData(ErrorCode::Success);        
        return $results;
    }
}