<?php

namespace Games\Players;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Games\Accessors\PlayerAccessor;
use Games\Consts\SyncRate;
use Games\Players\Exp\ExpBonusCalculator;
use Games\Players\Exp\PlayerEXP;
use Games\Pools\PlayerPool;
use stdClass;

class SyncRateUtility
{
    /**
     * @param int $type 同步率增加的種類。分為PVP增加(0)、PVE增加(1)、派遣增加(2)
     * @param ExpBonus $bonuses 效果集合，沒有加成可以不用給。
     */
    public static function GainSync(int $playerID, int $type, int $count = 1, ...$bonuses) : int
    {
        $rawSync = match($type)
        {
            SyncRate::PVP => 
            SyncRate::PVPMultiplier * sqrt(sqrt(max(self::GetPlayerTradeCount($playerID),1))),

            SyncRate::PVE =>
            SyncRate::PVEMultiplier * sqrt(sqrt(max(self::GetPlayerTradeCount($playerID),1))),
            
            SyncRate::Expedition => 
            SyncRate::ExpeditionMultiplier * sqrt(sqrt(max(self::GetPlayerTradeCount($playerID),1))),
        };

        $expCalculator = new ExpBonusCalculator($rawSync);
        
        $playerAccessor = new PlayerAccessor();

        $row = $playerAccessor->rowHolderByPlayerID($playerID);

        foreach($bonuses as $bonus)
        {
            $expCalculator->AddBonus($bonus);
        }

        $syncIncrease = 0;
        for($i = 0; $i < $count; $i++)
        $syncIncrease += $expCalculator->Process()->exp;

        $exp = ($syncIncrease + $row->SyncRate);
        $exp = PlayerEXP::Clamp(SyncRate::Max,SyncRate::Min,$exp);
        $playerAccessor->ModifySyncByPlayerID($playerID,['SyncRate' => $exp]);
        PlayerPool::Instance()->Delete($playerID);
        // $this->ResetInfo();
        return $syncIncrease;        

    }

    public static function GetPlayerTradeCount(int $playerID) : int
    {
        $accessor = new PDOAccessor(EnvVar::DBMain);
        $row = $accessor->FromTable('PlayerNFT')->WhereEqual('PlayerID',$playerID)->Fetch();
        return $row === false ? 0 : $row->TradeCount;
    }

}