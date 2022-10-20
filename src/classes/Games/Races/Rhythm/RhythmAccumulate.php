<?php

namespace Games\Races\Rhythm;

use Games\Consts\RaceValue;
use Games\Pools\RaceVerifyPool;
use Games\Pools\RaceVerifyScenePool;
use Games\Races\Holders\RacePlayerHolder;
use Games\Races\RaceHandler;
use Games\Races\RaceVerifyHandler;
use stdClass;

/**蓄力 */
class RhythmAccumulate implements IRhythm
{
    private RacePlayerHolder|stdClass $racePlayerInfo;
    private const milestone1 = 0.33;
    private const milestone2 = 0.66;
    public function __construct(RacePlayerHolder|stdClass $racePlayerInfo)
    {
        $this->racePlayerInfo = $racePlayerInfo;
    }
    public function GetRhythm(): int
    {
        if($this->racePlayerInfo->hp <= 0) return RaceValue::NormalSpeed;

        $raceHandler = new RaceHandler($this->racePlayerInfo->race);
        $raceVerifySceneInfos = RaceVerifyScenePool::Instance()->{$raceHandler->GetInfo()->scene};
        $raceVerifySceneInfo = $raceVerifySceneInfos->{$this->racePlayerInfo->trackNumber};
        $totalDistance = $raceVerifySceneInfo->total - $raceVerifySceneInfo->begin;
        $verifyInfo = RaceVerifyPool::Instance()->{$this->racePlayerInfo->id};
        $currentDistance = $verifyInfo->serverDistance;
        $progress = $currentDistance / $totalDistance;
        return match(true)
        {
            $progress < self::milestone1 => RaceValue::RetainStrength,
            $progress >= self::milestone1 && $progress < self::milestone2 => RaceValue::NormalSpeed,
            $progress >= self::milestone2 => RaceValue::Sprint,
            default => RaceValue::RetainStrength,
        };
    }
} 