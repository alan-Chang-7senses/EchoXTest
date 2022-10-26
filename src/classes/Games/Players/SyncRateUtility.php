<?php

namespace Games\Players;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Games\Accessors\PlayerAccessor;
use Games\Consts\AbilityFactor;
use Games\Consts\SyncRate;
use Games\Players\Exp\ExpBonusCalculator;
use Games\Players\Exp\PlayerEXP;
use Games\Players\Holders\PlayerInfoHolder;
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
            SyncRate::PVP => SyncRate::PVPMultiplier * max(self::GetPlayerTradeCount($playerID),1),
            SyncRate::PVE => SyncRate::PVEMultiplier * max(self::GetPlayerTradeCount($playerID),1),
            SyncRate::Expedition => SyncRate::ExpeditionMultiplier * max(self::GetPlayerTradeCount($playerID),1),
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

        /**同步率應介於0~1之間 */
    public static function ApplySyncRateBonus(PlayerInfoHolder|stdClass $holder, int|float $syncRate) : void
    {
        $abilityDesc = self::GetAbilityDesc($holder);
        match(true)
        {
            self::IsBetween(AbilityFactor::SyncRateTypeMax[0],AbilityFactor::SyncRateTypeMax[1],$syncRate)
            => self::ModifyPlayerValueByValueID($abilityDesc[0],$holder,AbilityFactor::SyncRateBonus),

            self::IsBetween(AbilityFactor::SyncRateTypeSecond[0],AbilityFactor::SyncRateTypeSecond[1],$syncRate)
            => self::ModifyPlayerValueByValueID($abilityDesc[1],$holder,AbilityFactor::SyncRateBonus),

            self::IsBetween(AbilityFactor::SyncRateTypeThird[0],AbilityFactor::SyncRateTypeThird[1],$syncRate)
            => self::ModifyPlayerValueByValueID($abilityDesc[2],$holder,AbilityFactor::SyncRateBonus),

            self::IsBetween(AbilityFactor::SyncRateTypeFourth[0],AbilityFactor::SyncRateTypeFourth[1],$syncRate)
            => self::ModifyPlayerValueByValueID($abilityDesc[3],$holder,AbilityFactor::SyncRateBonus),

            self::IsBetween(AbilityFactor::SyncRateTypeFifth[0],AbilityFactor::SyncRateTypeFifth[1],$syncRate)
            => self::ModifyPlayerValueByValueID($abilityDesc[4],$holder,AbilityFactor::SyncRateBonus),

            default => null,
        };
    }
    
    /**算出數值大小排名。數值相同依照企劃說明排序。回傳陣列內容數字依照常數定義 */
    public static function GetAbilityDesc(PlayerInfoHolder|stdClass $info) : array
    {
        $abilities = 
        [
            [AbilityFactor::Velocity => $info->velocity],
            [AbilityFactor::Stamina => $info->stamina],
            [AbilityFactor::BreakOut => $info->breakOut],
            [AbilityFactor::Will => $info->will],
            [AbilityFactor::Intelligent => $info->intelligent],
        ];

        usort($abilities,function($a, $b)
        {
            $aVal = array_values($a)[0];
            $bVal = array_values($b)[0];
            if($aVal < $bVal)return 1;
            if($aVal == $bVal)
            {
                $akey = array_keys($a)[0];
                $bkey = array_keys($b)[0];
                return $akey > $bkey ? 1 : -1;
            }
            return -1;
        });
        $rt = [];
        foreach($abilities as $ability)$rt[] = array_keys($ability)[0];        
        return $rt;
    }

    public static function IsBetween(int|float $max, int|float $min, int|float $value)
    {
        return ($value >= $min) && ($value <= $max);
    }

    private static function ModifyPlayerValueByValueID(int $valueID,PlayerInfoHolder|stdClass $holder,int|float $modifyCoefficient)
    {
        match($valueID)
        {
            AbilityFactor::Velocity => $holder->velocity = (float)number_format($modifyCoefficient * $holder->velocity,AbilityFactor::Decimals),
            AbilityFactor::Stamina => $holder->stamina = (float)number_format($modifyCoefficient * $holder->stamina,AbilityFactor::Decimals),
            AbilityFactor::BreakOut => $holder->breakOut = (float)number_format($modifyCoefficient * $holder->breakOut,AbilityFactor::Decimals),
            AbilityFactor::Will => $holder->will = (float)number_format($modifyCoefficient * $holder->will,AbilityFactor::Decimals),
            AbilityFactor::Intelligent => $holder->intelligent = (float)number_format($modifyCoefficient * $holder->intelligent,AbilityFactor::Decimals),
        };
        
    }

}