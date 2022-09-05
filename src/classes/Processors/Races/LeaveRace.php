<?php

namespace Processors\Races;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\Globals;
use Games\Consts\RaceValue;
use Games\Exceptions\RaceException;
use Games\Pools\RacePlayerPool;
use Games\Pools\RacePool;
use Games\Pools\UserPool;
use Holders\ResultData;
/**
 * Description of LeaveRace
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class LeaveRace extends BaseRace{
    
    public function Process(): ResultData {
        
        $racePool = RacePool::Instance();
        $raceInfo = $racePool->{$this->userInfo->race};
        if($raceInfo->status == RaceValue::StatusFinish) throw new RaceException(RaceException::Finished);
        
        $accessor = new PDOAccessor(EnvVar::DBMain);
        
        $userInfo = $this->userInfo;
        $raceID = $userInfo->race;
        $racePlayerID = $accessor->Transaction(function () use ($accessor, $userInfo){
            
            $currentTime = $GLOBALS[Globals::TIME_BEGIN];
            
            $race = $accessor->FromTable('Races')->WhereEqual('RaceID', $userInfo->race)->ForUpdate()->Fetch();
            $racePlayerIDs = json_decode($race->RacePlayerIDs);
            $racePlayerID = $racePlayerIDs->{$userInfo->player};
            unset($racePlayerIDs->{$userInfo->player});
            $accessor->Modify(['RacePlayerIDs' => json_encode($racePlayerIDs)]);
            
            $accessor->ClearCondition();
            $accessor->FromTable('RacePlayer')->WhereEqual('RacePlayerID', $racePlayerID)->Modify([
                'Status' => RaceValue::StatusGiveUp,
                'UpdateTime' => $currentTime,
            ]);
            
            $accessor->ClearCondition();
            $accessor->FromTable('Users')->WhereEqual('UserID', $userInfo->id, 'id')->Modify([
                'Race' => RaceValue::NotInRace,
                'Lobby' => RaceValue::NotInLobby,
                'Room' => RaceValue::NotInRoom,
                'UpdateTime' => $currentTime,
            ]);
            
            return $racePlayerID;
        });
        
        UserPool::Instance()->Delete($this->userInfo->id);
        $racePool->Delete($raceID);
        RacePlayerPool::Instance()->Delete($racePlayerID);
        
        $result = new ResultData(ErrorCode::Success);
        return $result;
    }
}
