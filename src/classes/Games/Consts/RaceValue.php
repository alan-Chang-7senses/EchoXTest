<?php

namespace Games\Consts;
/**
 * Description of RaceValue
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class RaceValue {
    
    const NotInRace = 0;
    
    const StatusInit = 0;
    const StatusUpdate = 1;
    const StatusReach = 2;
    const StatusFinish = 3;
    
    const StepFinishSuccess = 3;

    const BaseEnergyCount = 12;
    const EnergyTypeCount = 4;
    
    const HPDecimals = 2;
    
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
    const WindEffectFactor = [
        SceneValue::Tailwind => 0.01,
        SceneValue::Crosswind => 0,
        SceneValue::Headwind => -0.01,
    ];
    
    /* 比賽節奏 全力衝刺 */
    const Sprint = 1;
    /* 比賽節奏 平常速度 */
    const NormalSpeed = 2;
    /* 比賽節奏 保留體力 */
    const RetainStrength = 3;
}
