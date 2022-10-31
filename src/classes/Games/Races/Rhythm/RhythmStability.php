<?php

namespace Games\Races\Rhythm;

use Games\Consts\RaceValue;

class RhythmStability implements IRhythm
{
    // private RacePlayerHolder $racePlayerInfo;
    // public function __construct(RacePlayerHolder $racePlayerInfo)
    // {
    //     $this->racePlayerInfo = $racePlayerInfo;
    // }
    public function GetRhythm(): int
    {
        return RaceValue::NormalSpeed;        
    }
} 