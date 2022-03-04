<?php

namespace Processors\Races;

use Consts\ErrorCode;
use Games\Consts\RaceValue;
use Games\Races\RaceHandler;
use Games\Races\RacePlayerHandler;
use Holders\ResultData;
/**
 * Description of ReachEnd
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class ReachEnd extends BaseRace{
    
    public function Process(): ResultData {
        
        $raceHandler = new RaceHandler($this->userInfo->race);
        $racePlayerID = $raceHandler->GetInfo()->racePlayers->{$this->userInfo->player};
        $racePlayerHandler = new RacePlayerHandler($racePlayerID);
        $racePlayerHandler->SaveData(['status' => RaceValue::StatusReach]);
        
        $result = new ResultData(ErrorCode::Success);
        return $result;
    }
}
