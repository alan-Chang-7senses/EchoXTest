<?php
namespace Games\Players\Exp;

use Accessors\MemcacheAccessor;
use Accessors\PDOAccessor;
use Consts\EnvVar;
use Games\Consts\PlayerValue;

class PlayerEXP
{

    private const LevelUnit = 1;
    private const ExpUnit = 1;


    /**取得目前階段能到達最高的經驗值 */
    public static function GetMaxEXP(int $currentRank) : int 
    {
        $allLevelMaxExp = self::GetData();
        return $allLevelMaxExp[PlayerValue::RankMaxLevel[$currentRank] - self::LevelUnit] + self::ExpUnit;
    }

    /**取得是否在目前階段已滿等 */
    public static function IsLevelMax(int $currentExp,int $currentRank) : bool
    {
        $allLevelMaxExp = self::GetData();
        return self::GetMaxEXP($currentRank) <= $currentExp;
    }

    /**取得加上經驗值後新的等級 */
    public static function GetLevel(int $exp,int $currentRank, int $currentLevel = PlayerValue::LevelMin) : int
    {
        $allLevelMaxExp = self::GetData();
        $maxLevel = PlayerValue::RankMaxLevel[$currentRank];
        for($i = $currentLevel; $i < $maxLevel; $i++)
        {
            if($exp <= $allLevelMaxExp[$i])return $i;
        }
        return $maxLevel;
    }

    /**取得距離下個等級還需要多少經驗值，經驗值無法再上升(包含階段滿等)回傳false */
    public static function GetNextLevelRequireEXP(int $currentLevel, int $currentRank,int $currentExp) : int | bool
    {
        $allLevelMaxExp = self::GetData();
        if(self::IsLevelMax($currentExp,$currentRank))return false;
        return $allLevelMaxExp[$currentLevel] - $currentExp + self::ExpUnit;
    }

    public static function Clamp(int|float $max, int|float $min, int|float $value)
    {
        return max($min, min($max, $value));
    }

    private static function GetData()
    {        
        $mem = MemcacheAccessor::Instance();
        $key = 'playerEXP_';
        $data = $mem->get($key);
        if($data === false)
        {
            self::SetData();
            $data = $mem->get($key);
        }        
        return (array)json_decode($data);
    }
    private static function SetData()
    {
        $key = 'playerEXP_';
        $allLevelMaxExp = [];
        $accessor = new PDOAccessor(EnvVar::DBStatic);
        $allLevelRequireEXP = $accessor->FromTable('LevelUpEXP')->FetchAll();
        foreach($allLevelRequireEXP as $requireEXP)
        {
            if($requireEXP->Level == PlayerValue::LevelMin)
            {
                $allLevelMaxExp[$requireEXP->Level] = $requireEXP->RequireEXP - self::ExpUnit;
                continue;
            }
            $previousElement = $allLevelMaxExp[$requireEXP->Level - 1];
            $allLevelMaxExp[$requireEXP->Level] = $previousElement + $requireEXP->RequireEXP; 
        }
        MemcacheAccessor::Instance()->set($key,json_encode($allLevelMaxExp));
    }

}