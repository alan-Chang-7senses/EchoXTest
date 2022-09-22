<?php

namespace Games\Random;

class RandomUtility
{
    const RandomMin = 0;
    const RandomMax = 99.999999;
    /** true : 中
     *  false：沒中
     */
    public static function DicePercentage(float $percentage) : bool
    {
        if($percentage < 0 || $percentage > 100)
        {
            //報錯，輸入參數不可超過0~100
        }        
        return self::RandomFloat(self::RandomMin,self::RandomMax) < $percentage;
    }


    public static function RandomFloat($st_num,$end_num,$mul=1000000) : float
    {
        if ($st_num>$end_num) return false;
        return mt_rand($st_num*$mul,$end_num*$mul)/$mul;
    }

    public static function GetRandomObject(...$object) : mixed
    {
        return $object[rand(0,count($object) - 1)];
    }
}
