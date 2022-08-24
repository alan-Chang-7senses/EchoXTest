<?php
namespace Games\Players\Exp;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Games\Consts\PlayerValue;

class PlayerEXP
{
    /**累進等級表 */
    private static array $allLevelMaxExp;

    private const LevelUnit = 1;
    private const ExpUnit = 1;

    private static function InitAllLevelMaxExp() : void
    {        
        $accessor = new PDOAccessor(EnvVar::DBStatic);
        $allLevelRequireEXP = $accessor->FromTable('LevelUpEXP')->FetchAll();
        foreach($allLevelRequireEXP as $requireEXP)
        {
            if($requireEXP->Level == PlayerValue::LevelMin)
            {
                self::$allLevelMaxExp[$requireEXP->Level] = $requireEXP->RequireEXP - self::ExpUnit;
                continue;
            }
            $previousElement = self::$allLevelMaxExp[$requireEXP->Level - 1];
            self::$allLevelMaxExp[$requireEXP->Level] = $previousElement + $requireEXP->RequireEXP; 
        }
    }

    /**取得目前階段能到達最高的經驗值 */
    public static function GetMaxEXP(int $currentRank) : int 
    {
        if(empty($allLevelMaxExp))self::InitAllLevelMaxExp();
        return self::$allLevelMaxExp[PlayerValue::RankMaxLevel[$currentRank] - self::LevelUnit] + self::ExpUnit;
    }

    /**取得是否在目前階段已滿等 */
    public static function IsLevelMax(int $currentExp,int $currentRank) : bool
    {
        if(empty($allLevelMaxExp))self::InitAllLevelMaxExp();
        return self::GetMaxEXP($currentRank) <= $currentExp;
    }

    /**取得加上經驗值後新的等級 */
    public static function GetLevel(int $exp,int $currentRank, int $currentLevel = PlayerValue::LevelMin) : int
    {
        if(empty($allLevelMaxExp))self::InitAllLevelMaxExp();
        $maxLevel = PlayerValue::RankMaxLevel[$currentRank];
        for($i = $currentLevel; $i < $maxLevel; $i++)
        {
            if($exp <= self::$allLevelMaxExp[$i])return $i;
        }
        return $maxLevel;
    }

    /**取得距離下個等級還需要多少經驗值，經驗值無法再上升(包含階段滿等)回傳false */
    public static function GetNextLevelRequireEXP(int $currentLevel, int $currentRank,int $currentExp) : int | bool
    {
        if(empty($allLevelMaxExp))self::InitAllLevelMaxExp();
        if(self::IsLevelMax($currentExp,$currentRank))return false;
        return self::$allLevelMaxExp[$currentLevel] - $currentExp + self::ExpUnit;
    }

}