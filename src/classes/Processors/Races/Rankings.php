<?php

namespace Processors\Races;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\Globals;
use Games\Consts\RaceValue;
use Games\Exceptions\RaceException;
use Games\Pools\RacePlayerPool;
use Games\Races\RaceHandler;
use Games\Races\RacePlayerHandler;
use Generators\ConfigGenerator;
use Generators\DataGenerator;
use Helpers\InputHelper;
use Holders\ResultData;
/**
 * Description of UpdateRanking
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class Rankings extends BaseRace{
    
    public function Process(): ResultData {
        
        $players = json_decode(InputHelper::post('players'));
        if(!is_array($players) || count($players) > ConfigGenerator::Instance()->AmountRacePlayerMax) throw new RaceException(RaceException::IncorrectPlayerNumber);
        DataGenerator::ExistProperties($players[0], ['id', 'ranking']);
        
        $raceHandler = new RaceHandler($this->userInfo->race);
        
        $raceInfo = $raceHandler->GetInfo();
        if($raceInfo->status == RaceValue::StatusFinish) throw new RaceException(RaceException::Finished);
        
        $rankings = [];
        $offsides = [];
        $takenOvers = [];
        foreach ($players as $player){
            if(!property_exists($raceInfo->racePlayers, $player->id)) continue;
            
            $racePlayerID = $raceInfo->racePlayers->{$player->id};
            $rankings[$racePlayerID] = $player->ranking;

            $rph = new RacePlayerHandler($racePlayerID);
            $rpInfo = $rph->GetInfo();
            $offside = $rpInfo->ranking - $player->ranking;                        
            if($offside > 0) $offsides[$racePlayerID] = $rpInfo->offside + $offside;
            elseif($offside < 0) $takenOvers[$racePlayerID] = $rpInfo->takenOver + (-$offside);                         
        }
        
        asort($rankings);
        $keys = array_keys($rankings);
        $n = 1;
        foreach ($keys as $racePlayerID){
            $rankings[$racePlayerID] = $n++;
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
        return $result;
    }
}
