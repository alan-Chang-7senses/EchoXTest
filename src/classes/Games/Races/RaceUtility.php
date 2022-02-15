<?php
namespace Games\Races;

use Games\Consts\DNASun;
use Games\Consts\RaceValue;
use stdClass;
use Games\Players\PlayerUtility;
use Games\Consts\SceneValue;
/**
 * Description of RaceUtility
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class RaceUtility {
    
    public static function RandomEnergy(int $slotNumber) : array{
        
        $count = RaceValue::BaseEnergyCount + $slotNumber;
        $energy = array_fill(0, RaceValue::EnergyTypeCount, 0);
        $max = RaceValue::EnergyTypeCount - 1;
        for($i = 0; $i < $count; ++$i){
            ++$energy[random_int(0, $max)];
        }
        
        return $energy;
    }
    
    /**
     * 坡度值
     * @param int $trackType 賽道（地形）坡度類型
     * @return float
     */
    public static function SlopeValue(int $trackType) : float {
        return RaceValue::TrackType[$trackType];
    }
    
    /**
     * 氣候加速值
     * @param int $weather 氣候
     * @return float
     */
    public static function ClimateAccelerationValue(int $weather) : float {
        return RaceValue::ClimateAccelerations[$weather];
    }
    
    /**
     * 氣候衰減值
     * @param int $weather 氣候
     * @return float
     */
    public static function ClimateLoseValue(int $weather) : float {
        return RaceValue::ClimateLoses[$weather];
    }

    /**
     * 風向影響值
     * @param int $windDirection 場景風向
     * @param int $playerDirection 角色方向
     * @param float $windSpeed 風速
     * @return float
     */
    public static function WindEffectValue(int $playerWindDirectin, float $windSpeed) : float {
        return RaceValue::WindEffectFactor[$playerWindDirectin] * $windSpeed;
    }

    public static function ValueS(int $trackType, int $weather, stdClass $player, float $slope, float $windEffect) : float{
        if($player->hp <= 0) return self::StraightDepletedValueS ($windEffect);
    }


    public static function StraightValueH(float $sun) {
        
    }
    
    public static function StraightValueS(float $sun) {
        
    }
    
    public static function StraightDepletedValueS(stdClass $player, float $climateAccelation, float $slope, float $windEffect, int $env, int $weather, int $trackType) : float{
//        $velocity = PlayerUtility::AdaptValueByPoint($player->velocity);
//        $will = PlayerUtility::AdaptValueByPoint($player->will);
//        return $climateAccelation * (( + ));
    }

    public static function CurvidValueH(float $sun) {
        
    }
    
    public static function CurvidValueS(float $sun) {
        
    }
    
    public static function CurvidDepletedValueS(float $sun) {
        
    }
}
