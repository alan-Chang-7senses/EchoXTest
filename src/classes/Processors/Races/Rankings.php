<?php

namespace Processors\Races;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\Globals;
use Games\Consts\RaceValue;
use Games\Exceptions\RaceException;
use Games\Pools\RacePlayerPool;
use Games\Pools\RaceVerifyPool;
use Games\Pools\RaceVerifyScenePool;
use Games\Races\RaceHandler;
use Games\Races\RacePlayerHandler;
use Holders\ResultData;
/**
 * Description of UpdateRanking
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class Rankings extends BaseRace{
    
    public function Process(): ResultData {
        
        $raceHandler = new RaceHandler($this->userInfo->race);
        
        $raceInfo = $raceHandler->GetInfo();
        if($raceInfo->status == RaceValue::StatusFinish) throw new RaceException(RaceException::Finished);
        
        $rankings = [];
        $offsides = [];
        $takenOvers = [];
        $progress = [];
        foreach($raceInfo->racePlayers as $racePlayerID)
        {
            $racePlayerInfo = (new RacePlayerHandler($racePlayerID))->GetInfo();
            $raceVerifySceneInfos = RaceVerifyScenePool::Instance()->{$raceInfo->scene};
            $raceVerifySceneInfo = $raceVerifySceneInfos->{$racePlayerInfo->trackNumber};
            $totalDistance = $raceVerifySceneInfo->total - $raceVerifySceneInfo->begin;
            // $verifyInfo = $accessor->FromTable('RaceVerify')->WhereEqual('RacePlayerID',$racePlayerInfo->id)
            //         ->Fetch(); 
            $verifyInfo = RaceVerifyPool::Instance()->{$racePlayerInfo->id}; 
            $currentDistance = $verifyInfo->serverDistance;
            $progress[$racePlayerInfo->id] = $currentDistance / $totalDistance;
        }

        uasort($progress,function($a, $b)
        {
            if($a < $b)return 1;
            return -1;
        });
        $rank = 1;
        foreach(array_keys($progress) as $racePlayer)
        {
            $rankings[$racePlayer] = $rank;
            $rank++;
        }
        //給前端使用
        $rankingsResult = [];


        foreach ($rankings as $racePlayerID => $rank){

            $racePlayerHandler = new RacePlayerHandler($racePlayerID);
            $racePlayerInfo = $racePlayerHandler->GetInfo();
            $offside = $racePlayerInfo->ranking - $rank;                        
            if($offside > 0) $offsides[$racePlayerID] = $racePlayerInfo->offside + $offside;
            elseif($offside < 0) $takenOvers[$racePlayerID] = $racePlayerInfo->takenOver + (-$offside);                         

            $rankingsResult[$racePlayerInfo->player] = $rank;
        }
        
        $accessor = new PDOAccessor(EnvVar::DBMain);
        $accessor->Transaction(function() use ($accessor, $rankings, $offsides, $takenOvers){            
            
            $rows = $accessor->FromTable('RacePlayer')->WhereIn('RacePlayerID', array_keys($rankings))->ForUpdate()->FetchAll();
            
            foreach($rows as $row){
                
                if($row->Status == RaceValue::StatusReach) continue;
                $binds = 
                [
                    'Ranking' =>$rankings[$row->RacePlayerID],
                    'Status' => RaceValue::StatusUpdate,
                    'UpdateTime' => $GLOBALS[Globals::TIME_BEGIN]
                ];
                if(array_key_exists($row->RacePlayerID,$offsides))$binds['Offside'] = $offsides[$row->RacePlayerID];
                elseif(array_key_exists($row->RacePlayerID,$takenOvers))$binds['TakenOver'] = $takenOvers[$row->RacePlayerID];

                $accessor->ClearCondition();
                $accessor->FromTable('RacePlayer')->WhereEqual('RacePlayerID', $row->RacePlayerID)->Modify($binds);

            }
        });
        
        $racePlayerPool = RacePlayerPool::Instance();
        foreach($raceInfo->racePlayers as $racePlayerID){
            $racePlayerPool->Delete($racePlayerID);
        }
        
        $result = new ResultData(ErrorCode::Success);
        $result->rankings = $rankingsResult;


        return $result;
    }
}
