<?php

namespace Processors\Races;

use Consts\ErrorCode;
use Games\Consts\RaceValue;
use Games\Exceptions\RaceException;
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
        $raceInfo = $raceHandler->GetInfo();
        
        $racePlayerID = $raceInfo->racePlayers->{$this->userInfo->player};
        $racePlayerHandler = new RacePlayerHandler($racePlayerID);
        
        if($raceInfo->status == RaceValue::StatusFinish) throw new RaceException(RaceException::Finished);
        
        $currentTime = microtime(true);
        $racePlayerHandler->SaveData(['status' => RaceValue::StatusReach, 'updateTime' => $currentTime, 'finishTime' => $currentTime]);
        
        $result = new ResultData(ErrorCode::Success);
        return $result;
    }
}
