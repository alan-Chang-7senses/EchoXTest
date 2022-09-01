<?php

namespace Processors\Player;

use Consts\ErrorCode;
use Consts\Sessions;
use Games\Consts\PlayerValue;
use Games\Exceptions\UserException;
use Games\Players\Exp\PlayerEXP;
use Games\Players\PlayerAbility;
use Games\Players\PlayerHandler;
use Games\Players\UpgradeData;
use Games\Users\UserBagHandler;
use Games\Users\UserHandler;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\BaseProcessor;
use stdClass;

class UpgradePage extends BaseProcessor{

    public function Process(): ResultData
    {

        $playerID = InputHelper::post('playerID');
        $userID = $_SESSION[Sessions::UserID];
        $userInfo = (new UserHandler($userID))->GetInfo();
        $userPlayers = $userInfo->players;
        $userBagHandler = new UserBagHandler($userID);

        if(!in_array($playerID,$userPlayers))
        throw new UserException(UserException::NotHoldPlayer,['player' => $playerID]);
        
        $results = new ResultData(ErrorCode::Success);
        $playerHandler = new PlayerHandler($playerID);
        $playerInfo = $playerHandler->GetInfo();
        $results->rank = $playerInfo->rank;
        $results->currentCoin = $userInfo->coin;
        $results->currentExp = $playerInfo->exp;
        $results->maxLevel = PlayerValue::RankMaxLevel[$playerInfo->rank];        
        $upgradeData = (array)UpgradeData::GetData()->itemChargeTable;
        $itemData = [];
        $expTotal = 0;
        $expToCost = PlayerEXP::GetMaxEXP($playerInfo->rank) - $playerInfo->exp;
        $i = 0;
        uasort($upgradeData, function($a,$b)
        {
            return $a->EffectValue > $b->EffectValue ? -1 : 1;
        });
        foreach($upgradeData as $item)
        {
            $amount = $userBagHandler->GetItemAmount($item->ItemID);
            $itemData[$item->ItemID]['amount'] = $amount;
            $itemData[$item->ItemID]['cost'] = $item->Charge;
            $itemData[$item->ItemID]['exp'] = $item->EffectValue;
            $expTotal += $item->EffectValue * $amount;
            //一鍵添加
            $i++;
            if($expToCost <= 0)
            {
                $itemData[$item->ItemID]['autoAmount'] = 0;
                continue;
            }
            $quotient = floor($expToCost / $item->EffectValue);
            $costAmount = $quotient > $amount ? $amount : $quotient;
            $expToCost -= $costAmount * $item->EffectValue;
            $itemData[$item->ItemID]['autoAmount'] = $i == count($upgradeData) && $costAmount > 0 ? $costAmount + 1 : $costAmount;
        }

        $targetLevel = PlayerEXP::GetLevel($expTotal + $playerInfo->exp,$playerInfo->rank,$playerInfo->level);        
        $levelData = [];
        for($i = $playerInfo->level; $i <= $targetLevel;$i++)
        {
            $levelTemp = new stdClass();
            $levelTemp->level = $i;
            $levelTemp->values = PlayerAbility::GetAbilityValueByLevel($playerID,$i);
            $levelTemp->levelRequireExp = PlayerEXP::GetLevelRequireExp($i);                        
            $levelData[] = $levelTemp;
        }
        
        $results->levelData = $levelData;
        $results->itemData = $itemData;
        return $results;
    }
}