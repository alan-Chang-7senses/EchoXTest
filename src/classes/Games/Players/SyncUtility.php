<?php

namespace Games\Players;

use Games\Accessors\PlayerAccessor;
use Games\Consts\SyncRate;
use Games\Players\Exp\ExpBonusCalculator;
use Games\Players\Exp\PlayerEXP;
use Games\Pools\PlayerPool;
use stdClass;

class SyncUtility
{
    /**
     * @param int $type 同步率增加的種類。分為PVP增加(0)、PVE增加(1)、派遣增加(2)
     * @param ExpBonus $bonuses 效果集合，沒有加成可以不用給。
     */
    public static function GainSync(int $playerID, int $type, ...$bonuses) : stdClass
    {
        $rawSync = match($type)
        {
            SyncRate::PVP => SyncRate::PVPMultiplier * self::GetPlayerTradeCount($playerID),
            SyncRate::PVE => SyncRate::PVEMultiplier * self::GetPlayerTradeCount($playerID),
            SyncRate::Expedition => SyncRate::ExpeditionMultiplier * self::GetPlayerTradeCount($playerID),
        };

        $expCalculator = new ExpBonusCalculator($rawSync);
        
        $playerAccessor = new PlayerAccessor();

        $row = $playerAccessor->rowHolderByPlayerID($playerID);

        foreach($bonuses as $bonus)
        {
            $expCalculator->AddBonus($bonus);
        }
        $rt = $expCalculator->Process();

        $exp = ($rt->exp + $row->SyncRate);
        $exp = PlayerEXP::Clamp(SyncRate::Max,SyncRate::Min,$exp);
        $playerAccessor->ModifySyncByPlayerID($playerID,['SyncRate' => $exp]);
        PlayerPool::Instance()->Delete($playerID);
        // $this->ResetInfo();
        return $rt;        

    }

    public static function GetPlayerTradeCount(int $playerID) : int
    {
        return 1;
    }

}