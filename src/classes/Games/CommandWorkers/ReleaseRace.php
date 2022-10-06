<?php

namespace Games\CommandWorkers;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\Globals;
use Games\Consts\RaceValue;
use Games\Pools\RacePlayerPool;
use Games\Pools\RacePool;
use Games\Pools\UserPool;
use Games\Races\RaceUtility;
use Generators\ConfigGenerator;
/**
 * Description of ReleaseRace
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class ReleaseRace extends BaseWorker{
    
    public function Process(): array {
        
        $accessor = new PDOAccessor(EnvVar::DBMain);
        
        $rows = $accessor->FromTable('Races')->WhereIn('Status', [RaceValue::StatusInit, RaceValue::StatusUpdate])
                ->OrderBy('RaceID', 'DESC')->Limit(100)->FetchAll();
        
        $finishTimelimit = $this->finish ?? ConfigGenerator::Instance()->TimelimitRaceFinish;
        $currentTime = $GLOBALS[Globals::TIME_BEGIN];
        $raceIDs = [];
        $racePlayerIDs = [];
        $playerIDs = [];
        
        foreach($rows as $row){
            
            if($currentTime - $row->CreateTime < $finishTimelimit)
                continue;
            
            $raceIDs[] = $row->RaceID;
            
            if($row->RacePlayerIDs === null)
                continue;
            
            $racePlayerIDsArr = get_object_vars(json_decode($row->RacePlayerIDs));
            $racePlayerIDs = array_merge($racePlayerIDs, array_values($racePlayerIDsArr));
            $playerIDs = array_merge($playerIDs, array_keys($racePlayerIDsArr));
        }
        
        if(empty($raceIDs)) return [
            'race' => 'No race must release.',
            'currentTime' => $currentTime,
        ];
        
        $accessor->ClearAll();
        
        $rows = $accessor->FromTable('Users')->WhereIn('Race', $raceIDs)->FetchStyleAssoc()->FetchAll();
        $userIDs = array_column($rows, 'UserID');
        $accessor->Modify([
            'Race' => RaceValue::NotInRace,
            'Lobby' => RaceValue::NotInLobby,
            'Room' => RaceValue::NotInRoom,
            'UpdateTime' => $currentTime,
        ]);
        
        UserPool::Instance()->DeleteAll($userIDs);
        
        $accessor->ClearCondition()
                ->FromTable('Races')->WhereIn('RaceID', $raceIDs)
                ->Modify([
                    'Status' => RaceValue::StatusRelease,
                    'UpdateTime' => $currentTime,
                ]);
        
        RacePool::Instance()->DeleteAll($raceIDs);
        RacePlayerPool::Instance()->DeleteAll($racePlayerIDs);
        
        RaceUtility::FinishRestoreLevel($playerIDs);
        
        sort($raceIDs);
        sort($userIDs);
        sort($racePlayerIDs);
        sort($playerIDs);
        
        return [
            'race' => $raceIDs,
            'user' => $userIDs,
            'racePlayer' => $racePlayerIDs,
            'player' => $playerIDs,
            'currentTime' => $currentTime,
        ];
    }
}
