<?php

namespace Games\Consts;

/**
 * Description of SkillValue
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class SkillValue {
    
    const SkillH = 0.66;
    const SkillS = 6.6;
    
    const DivisorCooldown = 100;
    const DivisorDuration = 100;
    const DivisorLevel = 100;
    
    const NotInSlot = 0;
    const DurationForever = -1;
    
    const LevelMin = 1;
    const LevelMax = 5;
    
    const EnergyRed = 0;
    const EnergyYellow = 1;
    const EnergyBlue = 2;
    const EnergyGreen = 3;
    
    const EffectH = 101;
    const EffectS = 102;
    const EffectSPD = 111;
    const EffectPOW = 112;
    const EffectFIG = 113;
    const EffectINT = 114;
    const EffectSTA = 115;
    
    const EffectAdaptDune = 121;
    const EffectAdaptCraterLake = 122;
    const EffectAdaptVolcano = 123;
    const EffectAdaptTailwind = 131;
    const EffectAdaptHeadwind = 132;
    const EffectAdaptCrosswind = 133;
    const EffectAdaptSunny = 141;
    const EffectAdaptAurora = 142;
    const EffectAdaptSandDust = 143;
    const EffectAdaptFlat = 151;
    const EffectAdaptUpslope = 152;
    const EffectAdaptDownslope = 153;
    const EffectAdaptSun = 161;
    const EffectAdaptNight = 162;
    
    const EffectHP = 201;
    const EffectEnergyAll = 211;
    const EffectEnergyRed = 212;
    const EffectEnergyYellow = 213;
    const EffectEnergyBlue = 214;
    const EffectEnergyGreen = 215;
    
    const EffectWindTailwind = 501;
    const EffectWindCrosswind = 502;
    const EffectWindHeadwind = 503;
    const EffectWeatherSunny = 504;
    const EffectWeatherAurora = 505;
    const EffectWeatherSandDust = 506;
    
    const TargetSelf = 0;
    const TargetNext = 1;
    const TargetLast = 2;
    const TargetPrevious = 3;
    const TargetFirst = 4;
    const TargetOthers = 5;
    
    const MaxConditionNone = 0;
    const MaxConditionRank = 1;
    const MaxConditionTop = 2;
    const MaxConditionBotton = 3;    
    const MaxConditionOffside = 4;
    const MaxConditionHit = 5;
    const MaxConditionStraight = 11;
    const MaxConditionCurved = 12;
    const MaxConditionFlat = 21;
    const MaxConditionUpslope = 22;
    const MaxConditionDownslope = 23;
    const MaxConditionTailwind = 31;
    const MaxConditionHeadwind = 32;
    const MaxConditionCrosswind = 33;
    const MaxConditionSunny = 41;
    const MaxConditionAurora = 42;
    const MaxConditionSandDust = 43;
    const MaxConditionDune = 51;
    const MaxConditionCraterLake = 52;
    const MaxConditionVolcano = 53;

    const MaxConditionLead = 61;
    const MaxConditionBehind = 62;
    const MaxConditionLastRank = 63;
    const MaxConditionTakenOver = 64;
    const MaxConditionSpeedUp = 65;
    const MaxConditionMinusH = 66;


    const SpeedUpEffects = [
        self::EffectS,
        self::EffectSPD,
        self::EffectPOW,
        self::EffectAdaptDune,
        self::EffectAdaptCraterLake,
        self::EffectAdaptVolcano,
        self::EffectAdaptTailwind,
        self:: EffectAdaptHeadwind,
        self::EffectAdaptCrosswind,
        self::EffectAdaptSunny,
        self::EffectAdaptAurora,
        self::EffectAdaptSandDust,
        self::EffectAdaptFlat,
        self::EffectAdaptUpslope,
        self::EffectAdaptDownslope,
        self::EffectAdaptSun,
        self::EffectAdaptNight,
    ];

    const ReduceCostHEffects = [
        self::EffectH,
        self::EffectFIG,
        self::EffectINT,
        self::EffectAdaptDune,
        self::EffectAdaptCraterLake,
        self::EffectAdaptVolcano,
        self::EffectAdaptTailwind,
        self:: EffectAdaptHeadwind,
        self::EffectAdaptCrosswind,
        self::EffectAdaptSunny,
        self::EffectAdaptAurora,
        self::EffectAdaptSandDust,
        self::EffectAdaptFlat,
        self::EffectAdaptUpslope,
        self::EffectAdaptDownslope,
        self::EffectAdaptSun,
        self::EffectAdaptNight,
    ];


}
