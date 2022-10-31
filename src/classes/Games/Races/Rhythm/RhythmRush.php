<?php

namespace Games\Races\Rhythm;

use Games\Consts\RaceValue;
use Games\Races\Holders\RacePlayerHolder;
use stdClass;

class RhythmRush implements IRhythm
{
    private RacePlayerHolder|stdClass $racePlayerInfo;
    public function __construct(RacePlayerHolder|stdClass $racePlayerInfo)
    {
        $this->racePlayerInfo = $racePlayerInfo;
    }
    public function GetRhythm(): int
    {
        return $this->racePlayerInfo->hp > 0 ? RaceValue::Sprint : RaceValue::NormalSpeed;
    }
} 