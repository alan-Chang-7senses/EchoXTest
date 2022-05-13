<?php

namespace Processors\Races;

use Consts\ErrorCode;
use Consts\Globals;
use Games\Consts\RaceValue;
use Games\Exceptions\RaceException;
use Games\Races\RaceHandler;
use Games\Races\RacePlayerHandler;
use Helpers\InputHelper;
use Holders\ResultData;
/**
 * Description of ReachEnd
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class ReachEnd extends BaseRace{
    
    public function Process(): ResultData {
        
        $player = InputHelper::post('player');
        
        $raceHandler = new RaceHandler($this->userInfo->race);
        $raceInfo = $raceHandler->GetInfo();
        
        if(!property_exists($raceInfo->racePlayers, $player)) throw new RaceException(RaceException::PlayerNotInThisRace);
        $racePlayerID = $raceInfo->racePlayers->{$player};
        $racePlayerHandler = new RacePlayerHandler($racePlayerID);
        
        if($raceInfo->status == RaceValue::StatusFinish) throw new RaceException(RaceException::Finished);
        
        $racePlayerHandler->SaveData(['status' => RaceValue::StatusReach, 'finishTime' => $GLOBALS[Globals::TIME_BEGIN]]);
        
        $result = new ResultData(ErrorCode::Success);
        return $result;
    }
}
