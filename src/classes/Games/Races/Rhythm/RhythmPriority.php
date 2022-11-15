<?php

namespace Games\Races\Rhythm;

use Games\Consts\RaceValue;
use Games\Races\Holders\RacePlayerHolder;
use Games\Races\RaceHandler;
use stdClass;

/**優先 */
class RhythmPriority implements IRhythm
{
    private RacePlayerHolder|stdClass $racePlayerInfo;
    public function __construct(RacePlayerHolder|stdClass $racePlayerInfo)
    {
        $this->racePlayerInfo = $racePlayerInfo;
    }
    public function GetRhythm(): int
    {
        $raceHandler = new RaceHandler($this->racePlayerInfo->race);
        $isRankLead = $raceHandler->IsRankingLead($this->racePlayerInfo->ranking);

        return $isRankLead || $this->racePlayerInfo->hp <= 0 ? 
                RaceValue::NormalSpeed : RaceValue::Sprint;
    }
} 