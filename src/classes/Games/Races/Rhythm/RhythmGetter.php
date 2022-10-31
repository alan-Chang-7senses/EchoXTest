<?php

namespace Games\Races\Rhythm;

use Games\Consts\PlayerValue;
use Games\Races\RacePlayerHandler;

class RhythmGetter
{
    private IRhythm $rhythm;

    public function __construct(int $habit, int $racePlayerID)
    {
        match($habit)
        {
            PlayerValue::Rush => $this->rhythm = new RhythmRush((new RacePlayerHandler($racePlayerID))->GetInfo()),
            PlayerValue::Stability => $this->rhythm = new RhythmStability(),
            PlayerValue::Priority => $this->rhythm = new RhythmPriority((new RacePlayerHandler($racePlayerID))->GetInfo()),
            PlayerValue::Accumulate => $this->rhythm = new RhythmAccumulate((new RacePlayerHandler($racePlayerID))->GetInfo()),
        };        
    }

    public function GetRhythm() : int {return $this->rhythm->GetRhythm();}
}