<?php

namespace Games\Consts;
/**
 * Description of RaceValue
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class RaceValue {
    
    const NotInRace = 0;
    const BotMatch = -1;
    
    const ForeverAdditiveSec = 1000000000;
    
    const LaunchMaxNot = 0;
    const LaunchMaxYes = 1;
    const LaunchMaxFail = 0;
    const LaunchMaxSuccess = 1;
    
    const StatusInit = 0;
    const StatusUpdate = 1;
    const StatusReach = 2;
    const StatusFinish = 3;
    
    const StepFinishSuccess = 3;

    const BaseEnergyCount = 12;
    const EnergyTypeCount = 4;
    const EnergyFixedCount = 10;
    
    const DivisorHP = 100;
    const DivisorSkillDuration = 75;
    
    const SunNone = 100;
    const SunSame = 120;
    const SunDiff = 80;
    
    const TrackType = [
        SceneValue::Flat => 10,
        SceneValue::Upslope => 12,
        SceneValue::Downslope => 9,
    ];
    
    const ClimateAccelerations = [
        SceneValue::Sunny => 1,
        SceneValue::Aurora => 1.2,
        SceneValue::SandDust => 1,
    ];
    const ClimateLoses = [
        SceneValue::Sunny => 0,
        SceneValue::Aurora => 0,
        SceneValue::SandDust => 0.5,
    ];
    
    const WindCheckPositive = 2;
    const WindCheckReverse = 0;
    
    /* S 值 + 風速影響值 的速度上限 */
    const ValueSMax = 20;
    /* S 值 + 風速影響值 的速度下限 */
    const ValueSMin = 5;
    
    /* 比賽節奏 全力衝刺 */
    const Sprint = 1;
    /* 比賽節奏 平常速度 */
    const NormalSpeed = 2;
    /* 比賽節奏 保留體力 */
    const RetainStrength = 3;
    
    const PlayerEffectTypes = [
        SkillValue::EffectH,
        SkillValue::EffectS,
        SkillValue::EffectSPD,
        SkillValue::EffectPOW,
        SkillValue::EffectFIG,
        SkillValue::EffectINT,
        SkillValue::EffectSTA,
        SkillValue::EffectAdaptDune,
        SkillValue::EffectAdaptCraterLake,
        SkillValue::EffectAdaptVolcano,
        SkillValue::EffectAdaptTailwind,
        SkillValue::EffectAdaptHeadwind,
        SkillValue::EffectAdaptCrosswind,
        SkillValue::EffectAdaptSunny,
        SkillValue::EffectAdaptAurora,
        SkillValue::EffectAdaptSandDust,
        SkillValue::EffectAdaptFlat,
        SkillValue::EffectAdaptUpslope,
        SkillValue::EffectAdaptDownslope,
        SkillValue::EffectAdaptSun,
    ];
    
    const PlayerEffectOnceType = [
        SkillValue::EffectHP,
        SkillValue::EffectEnergyAll,
        SkillValue::EffectEnergyRed,
        SkillValue::EffectEnergyYellow,
        SkillValue::EffectEnergyBlue,
        SkillValue::EffectEnergyGreen,
    ];
    
    const WeatherEffect = [
        SkillValue::EffectWeatherSunny => SceneValue::Sunny,
        SkillValue::EffectWeatherAurora => SceneValue::Aurora,
        SkillValue::EffectWeatherSandDust => SceneValue::SandDust,
    ];
    
    const WindDirectionEffect = [
        SkillValue::EffectWindTailwind => SceneValue::Tailwind,
        SkillValue::EffectWindCrosswind => SceneValue::Crosswind,
        SkillValue::EffectWindHeadwind => SceneValue::Headwind,
    ];
}
