<?php

namespace Processors\Races;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\Globals;
use Games\Consts\RaceValue;
use Games\Consts\RaceVerifyValue;
use Games\Exceptions\RaceException;
use Games\Pools\RacePlayerPool;
use Games\Races\RaceHandler;
use Games\Races\RaceVerifyHandler;
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
        $distance = InputHelper::post('distance');
        
        $raceHandler = new RaceHandler($this->userInfo->race);
        $raceInfo = $raceHandler->GetInfo();
        
        $accessor = new PDOAccessor(EnvVar::DBMain);
        
        if(!property_exists($raceInfo->racePlayers, $player)){
            
            $row = $accessor->FromTable('RacePlayer')->WhereEqual('RaceID', $this->userInfo->race)->WhereEqual('PlayerID', $player)->Fetch();
            if(!empty($row) && $row->Status == RaceValue::StatusGiveUp) return new ResultData (ErrorCode::Success);
            
            throw new RaceException(RaceException::PlayerNotInThisRace, ['[player]' => $player]);
        }
        
        $racePlayerID = $raceInfo->racePlayers->{$player};
        
        //驗證是否結束,作弊就校正位置
        if (RaceVerifyHandler::Instance()->ReachEnd($racePlayerID, $distance) == RaceVerifyValue::VerifyCheat)
        {
            $result = new ResultData(ErrorCode::Success);            
            $result -> moveDistance = RaceVerifyHandler::Instance()->GetMoveDistance();
            return $result;
        }
        
        if($raceInfo->status == RaceValue::StatusFinish) throw new RaceException(RaceException::Finished);
        
        $accessor->ClearAll();
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
        
        RacePlayerPool::Instance()->Delete($racePlayerID);
        
        $result = new ResultData(ErrorCode::Success);
                


        return $result;
    }
}
