<?php

namespace Games\Consts;
/**
 * Description of RaceValue
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class RaceValue {
    
    const NotInRace = 0;
    const NotInLobby = 0;
    const NotInRoom = 0;
    const BotMatch = -1;
    const RacePlayerMin = 1;
    
    const NOSeasonID = -1;
    const NoTicketID = 0;
    
    const ForeverAdditiveSec = 1000000000;
    
    /* 能量再獲得次數上限 */
    const EnergyAgainCount = 1;
    /* 能量再獲得 最大數量 */
    const EnergyAgainMax = 22;
    /* 能量再獲得 最小數量 */
    const EnergyAgainMin = 3;
    
    const LaunchMaxNot = 0;
    const LaunchMaxYes = 1;
    const LaunchMaxFail = 0;
    const LaunchMaxSuccess = 1;
    
    const StatusInit = 0;
    const StatusUpdate = 1;
    const StatusReach = 2;
    const StatusFinish = 3;
    const StatusGiveUp = 4;
    const StatusStart = 5;
    const StatusRelease = 6;
    
    const StepFinishSuccess = 3;

    const BaseEnergyCount = 12;
    const EnergyTypeCount = 4;
    const EnergyFixedCount = 10;
    
    const DivisorHP = 100;
    const DivisorSkillDuration = 75;
    const DivisorSkillDurationForOther = 50;
    const DivisorLeadRate = 10000;
    const DivisorPercent = 100;
    
    const ValueMinHP = 0;
    
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
        SceneValue::Aurora => 1.1,
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

    /**直線x體力充沛 前係數 */
    const PositiveHPStraightFront = 0.55;
    /**直線x體力充沛 後係數 */
    const PositiveHPStraightBack = 0.33;
    /**直線x體力耗盡 前係數 */
    const MinusHPStraightFront = 0.25;
    /**直線x體力耗盡 後係數 */
    const MinusHPStraightBack = 0.25;
    /**彎道x體力充沛 前係數 */
    const PositiveHPCurveFront = 0.55;
    /**彎道x體力充沛 後係數 */
    const PositiveHPCurveBack = 0.33;
    /**彎道x體力耗盡 前係數 */
    const MinusHPCurveFront = 0.25;
    /**彎道x體力耗盡 後係數 */
    const MinusHPCurveBack = 0.25;

    /**體力充沛S基數 */
    const SValueBasePositiveHP = 2.5;
    /**體力耗盡S基數 */
    const SValueBaseMinusHP = 0;

    const LobbyNone = 0;
    const LobbyCoin = 1;
    const LobbyPT = 2;
    const LobbyStudy = 3;
    const LobbyCoinB = 4;
    const LobbyPetaTokenB = 5;
    const LobbyPVE = 6;
    
    const LobbyPlayerLevelConfig = [
        self::LobbyCoin => 'LobbyCoinPlayerLevel',
        self::LobbyPT => 'LobbyPTPlayerLevel',
        self::LobbyStudy => 'LobbyStudyPlayerLevel',
        self::LobbyCoinB => 'LobbyCoinPlayerLevel',
        self::LobbyPetaTokenB => 'LobbyPTPlayerLevel',
        self::LobbyPVE => 'LobbyPVEPlayerLevel',
    ];
    
    const LobbySkillLevelConfig = [
        self::LobbyCoin => 'LobbyCoinSkillLevel',
        self::LobbyPT => 'LobbyPTSkillLevel',
        self::LobbyStudy => 'LobbyStudySkillLevel',
        self::LobbyCoinB => 'LobbyCoinSkillLevel',
        self::LobbyPetaTokenB => 'LobbyPTSkillLevel',
        self::LobbyPVE => 'LobbyPVESkillLevel',
    ];
    
    const RoomIdle = 0;
    const RoomMatching = 1;
    const RoomFull = 2;
    const RoomClose = 3;   
    
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
        SkillValue::EffectAdaptNight,
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

    const HitConditionCount = 1;
    const TakenOverConditionCount = 1;
    
    const LeadRanking = [
        1 => 1,
        2 => 1,
        3 => 1,
        4 => 2,
        5 => 2,
        6 => 3,
        7 => 3,
        8 => 4,
    ];
}
