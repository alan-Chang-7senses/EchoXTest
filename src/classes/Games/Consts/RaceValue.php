<?php

namespace Games\Consts;
/**
 * Description of RaceValue
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class RaceValue {
    
    const NotInRace = 0;
    
    const BaseEnergyCount = 12;
    const EnergyTypeCount = 4;
    
    const SunNone = 100;
    const SunSame = 120;
    const SunDiff = 80;
    
    const TrackFlat = 1;
    const TrackUpslope = 2;
    const TrackDownslope = 3;
    const TrackType = [
        self::TrackFlat => 10,
        self::TrackUpslope => 12,
        self::TrackDownslope => 9,
    ];
    
    const ClimateSunny = 1;
    const ClimateAurora = 2;
    const ClimateSandDust = 3;
    const ClimateAccelerations = [
        self::ClimateSunny => 1,
        self::ClimateAurora => 1.2,
        self::ClimateSandDust => 1,
    ];
    const ClimateLoses = [
        self::ClimateSunny => 0,
        self::ClimateAurora => 0,
        self::ClimateSandDust => 0.5,
    ];
    
    const WindCheckPositive = 2;
    const WindChectReverse = 0;
    const WindEffectFactor = 0.01;
    const CrosswindFactor = 0;
}
