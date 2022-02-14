<?php
namespace Games\Races;

use Games\Consts\DNASun;
use Games\Consts\RaceValue;
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
     * 日照參數的太陽屬性值
     * @param int $climateLighting 當前場景氣候日照參數值
     * @param int $playerSun 角色太陽屬性
     * @return float 場景日照參數值
     */
    public static function SunValueByPlayer(int $climateLighting, int $playerSun) : float{
        return match ($climateLighting) {
            DNASun::Normal => RaceValue::SunNone,
            $playerSun => RaceValue::SunSame,
            default => RaceValue::SunDiff
        };
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
    public static function ClimateLose(int $weather) : float {
        return RaceValue::ClimateLoses[$weather];
    }

    /**
     * 風向影響值
     * @param int $windDirection 場景風向
     * @param int $playerDirection 角色方向
     * @param float $windSpeed 風速
     * @return float
     */
    public static function WindEffectValue(int $windDirection, int $playerDirection, float $windSpeed) : float {
        $factor = match (abs($windDirection - $playerDirection)) {
            RaceValue::WindCheckPositive => RaceValue::WindEffectFactor,
            RaceValue::WindChectReverse => -RaceValue::WindEffectFactor,
            default => RaceValue::CrosswindFactor,
        };
        return $factor * $windSpeed;
    }

    public static function StraightValueH(float $sun) {
        
    }
    
    public static function StraightValueS(float $sun) {
        
    }
    
    public static function StraightDepletedValueS(float $sun) {
        
    }

    public static function CurvidValueH(float $sun) {
        
    }
    
    public static function CurvidValueS(float $sun) {
        
    }
    
    public static function CurvidDepletedValueS(float $sun) {
        
    }
}
