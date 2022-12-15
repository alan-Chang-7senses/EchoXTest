<?php

namespace Processors\Player;

use Consts\ErrorCode;
use Consts\Sessions;
// use Games\Accessors\GameLogAccessor;
use Games\Accessors\AccessorFactory;
use Games\Accessors\UpgradeLogAccessor;
use Games\Consts\ItemValue;
use Games\Consts\UpgradeValue;
use Games\Exceptions\ItemException;
use Games\Exceptions\UserException;
use Games\Players\Exp\ExpBonus;
use Games\Players\PlayerHandler;
use Games\Players\UpgradeData;
use Games\Users\UserBagHandler;
use Games\Users\UserHandler;
use Games\Pools\UserPool;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\BaseProcessor;
use stdClass;

class UpgradeItem extends BaseProcessor{

    public function Process(): ResultData
    {
        //跟前端要道具名稱跟數量與培養模式，還有角色ID
        $playerID = InputHelper::post('playerID');
        $itemInfo = json_decode(InputHelper::post('item'));
        $useMode = InputHelper::post('mode');        

        $userID = $_SESSION[Sessions::UserID];
        $userBaghandler = new UserBagHandler($userID);
        $userHandler = new UserHandler($userID);
        $userInfo = $userHandler->GetInfo();
        $playerHandler = new PlayerHandler($playerID);
        $playerInfo = $playerHandler->GetInfo();
        $previousLevel = $playerInfo->level;
        if(!in_array($playerID,$userInfo->players))
        throw new UserException(UserException::NotHoldPlayer,['player' => $playerID]);
        
        $upgradeData = UpgradeData::GetData();
        $itemChargeTable = (array)$upgradeData->itemChargeTable;
        
        $expTotal = 0;
        $costTotal = 0;
        //檢查是否持有且充足        
        foreach($itemInfo as $itemID => $amount)
        {            
            if($userBaghandler->GetItemAmount($itemID) < $amount)
            throw new ItemException(ItemException::ItemNotEnough,['item' => $itemID]);
            if(!$userBaghandler->CheckItemEffectType($itemID,ItemValue::EffectExp))
            throw new ItemException(ItemException::UseItemError,['itemID' => $itemID]);
            //算出欲增加之經驗值量與金幣基數
            $expTotal += $itemChargeTable[$itemID]->EffectValue * $amount;
            $costTotal += $itemChargeTable[$itemID]->Charge * $amount;
        }
        $upgradeModeTable = (array)$upgradeData->upgradeModeTable;
        $chargeMultiply = $upgradeModeTable[$useMode]->ChargeMultiply / UpgradeValue::Divisor;
        $bigBonusProbability = $upgradeModeTable[$useMode]->BigBonusProbability;
        $ultimateBonusProbability = $upgradeModeTable[$useMode]->UltimateBonusProbability;
        $costTotal *= $chargeMultiply;
        if($userInfo->coin < $costTotal)
        throw new UserException(UserException::UserCoinNotEnough,['userID' => $userID]);
        

        
        $upgradeBonusTable = (array)$upgradeData->upgradeBonusTable;

        $bigSuccessData = $upgradeBonusTable[UpgradeValue::BonusBigSuccessID];
        $bigSuccess = new ExpBonus($bigSuccessData->BonusID,$bigSuccessData->Multiplier / UpgradeValue::Divisor,$bigBonusProbability,$ultimateBonusProbability);
        $ultimateSuccessData = $upgradeBonusTable[UpgradeValue::BonusUltimateSuccessId];
        $ultimateSuccess = new ExpBonus($ultimateSuccessData->BonusID,$ultimateSuccessData->Multiplier / UpgradeValue::Divisor,$ultimateBonusProbability);
        
        
        $expRt = $playerHandler->GainExp($expTotal,$bigSuccess,$ultimateSuccess);
        
        //扣道具
        $decItems = [];
        foreach($itemInfo as $itemID => $amount)
        {
            if($amount <= 0)continue;
            $itemTemp = new stdClass();
            $itemTemp->ItemID = $itemID;
            $itemTemp->Amount = $amount;
            $decItems[] = $itemTemp;
        }
        
        $userBaghandler->DecItems($decItems,ItemValue::CauseGainExp);
        //扣錢
        $remain = $userInfo->coin - $costTotal;
        $accessor = AccessorFactory::Main();
        $accessor->Transaction(function () use ($accessor, $userID, $remain) {
            $row = $accessor->FromTable('Users')->WhereEqual('UserID', $userID)->Modify(['coin' => $remain]);
            if($row === false)return; //角色不在User表
            UserPool::Instance()->Delete($userID);
        });
        
        //打包：成功模式、目前等級
        $results = new ResultData(ErrorCode::Success);        
        $results->bonus = [];
        if(!empty($expRt->bonus))
        {
            foreach($expRt->bonus as $bonus)
            {
                $results->bonus[] = $bonus->id;
            }
        }
        $results->level = $playerHandler->GetInfo()->level;
        $results->hasUpgrade = $previousLevel < $playerHandler->GetInfo()->level;
        $bonus = empty($results->bonus) ? [UpgradeValue::BonuSuccessID] : $results->bonus;
        // (new GameLogAccessor())->AddUpgradeLog($playerID,null,null,-$costTotal,$bonus,$expRt->gainAmount,null);
        (new UpgradeLogAccessor)->AddUpgradeLevelLog($playerID,$costTotal,$bonus,$expRt->gainAmount);
        return $results;
    }
}