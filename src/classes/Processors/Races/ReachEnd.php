<?php

namespace Processors\Races;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\Globals;
use Games\Consts\RaceValue;
use Games\Exceptions\RaceException;
use Games\Races\RaceHandler;
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
        
        if($raceInfo->status == RaceValue::StatusFinish) throw new RaceException(RaceException::Finished);
        
        $accessor = new PDOAccessor(EnvVar::DBMain);
        $accessor->Transaction(function() use ($accessor, $racePlayerID){
            
            $row = $accessor->FromTable('RacePlayer')->WhereEqual('RacePlayerID', $racePlayerID)->ForUpdate()->Fetch();
            if($row->Status == RaceValue::StatusReach) throw new RaceException (RaceException::PlayerReached);
            
            $accessor->ClearCondition();
            $accessor->FromTable('RacePlayer')->WhereEqual('RacePlayerID', $racePlayerID)->Modify([
                'Status' => RaceValue::StatusReach,
                'UpdateTime' => $GLOBALS[Globals::TIME_BEGIN],
                'FinishTime' => $GLOBALS[Globals::TIME_BEGIN],
            ]);
        });
        
        $result = new ResultData(ErrorCode::Success);
        return $result;
    }
}
