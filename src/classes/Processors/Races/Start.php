<?php

namespace Processors\Races;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\Globals;
use Games\Consts\RaceValue;
use Games\Exceptions\RaceException;
use Games\Pools\RacePool;
use Games\Races\RaceHP;
use Games\Races\RaceVerifyHandler;
use Holders\ResultData;
/**
 * Description of Start
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class Start extends BaseRace{
    
    public function Process(): ResultData {
        
        $racePool = RacePool::Instance();
        $raceInfo = $racePool->{$this->userInfo->race};
        
        $racePlayerIDs = array_values(get_object_vars($raceInfo->racePlayers));
        if(empty($racePlayerIDs)) throw new RaceException(RaceException::IncorrectPlayerNumber);
        
        $accessor = new PDOAccessor(EnvVar::DBMain);
        $accessor->FromTable('RacePlayer')->WhereIn('RacePlayerID', $racePlayerIDs)->Modify([
            'Status' => RaceValue::StatusStart,
            'StartTime' => $GLOBALS[Globals::TIME_BEGIN],
        ]);
        
        RaceVerifyHandler::Instance()->Start($racePlayerIDs);
        RaceHP::Instance()->Start($racePlayerIDs);
        
        return new ResultData(ErrorCode::Success);
    }
}
