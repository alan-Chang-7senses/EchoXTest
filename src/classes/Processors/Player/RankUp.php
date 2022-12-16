<?php

namespace Processors\Player;

use Consts\ErrorCode;
use Consts\Sessions;
use Games\Accessors\UpgradeLogAccessor;
use Games\Accessors\AccessorFactory;
use Games\Consts\ItemValue;
use Games\Consts\PlayerValue;
use Games\Consts\UpgradeValue;
use Games\Exceptions\ItemException;
use Games\Exceptions\PlayerException;
use Games\Exceptions\UserException;
use Games\Players\PlayerHandler;
use Games\Players\UpgradeItemsHandler;
use Games\Pools\PlayerPool;
use Games\Pools\RankUpItemsPool;
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


        
        $upgradeHandler = new UpgradeItemsHandler($playerInfo->rank + 1, $playerInfo->ele,RankUpItemsPool::Instance());

        $charge = $upgradeHandler->GetCoinCost();

        
        if($userInfo->coin < $charge)
        throw new UserException(UserException::UserCoinNotEnough,['userID' => $userID]);

        $searchItems = $upgradeHandler->GetUpgradeItems();

        //檢驗數量是否足夠
        foreach($searchItems as $itemID => $amount)
        {
            if($amount <= 0)continue;
            if($userBagHandler->GetItemAmount($itemID) < $amount)
            throw new ItemException(ItemException::ItemNotEnough,['item' => $itemID]);
        }        

        $rankData = $playerInfo->rank + UpgradeValue::RankUnit;
        AccessorFactory::Main()->executeBind('UPDATE PlayerLevel 
        SET `RANK` = '.$rankData.' WHERE PlayerID = :PlayerID',['PlayerID' => $playerID]);

        PlayerPool::Instance()->Delete($playerID);
        $userHandler->SaveData(['coin' => $userInfo->coin - $charge]);
        
        // 扣道具
        foreach($searchItems as $itemID => $amount)
        {
            if($amount <= 0)continue;
            $item = new stdClass();
            $item->ItemID = $itemID;
            $item->Amount = $amount;
            $userBagHandler->DecItems($item,ItemValue::CauseRankUp);
        }

        (new UpgradeLogAccessor())->AddUpgradeRankLog($playerID,$charge,UpgradeValue::RankUnit);
        $results = new ResultData(ErrorCode::Success);
        return $results;
    }
}