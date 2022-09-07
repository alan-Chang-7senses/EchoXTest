<?php

namespace Games\CommandWorkers;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\Globals;
use Games\Consts\RaceValue;
use Games\Pools\RacePlayerPool;
use Games\Pools\RacePool;
use Games\Pools\UserPool;
use Generators\ConfigGenerator;
/**
 * Description of ReleaseRace
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class ReleaseRace extends BaseWorker{
    
    public function Process(): array {
        
        $config = ConfigGenerator::Instance();
        
        $finishTimelimit = $this->finish ?? $config->TimelimitRaceFinish;
        $currentTime = $GLOBALS[Globals::TIME_BEGIN];
        
        
        $accessor = new PDOAccessor(EnvVar::DBMain);
        
        $rows = $accessor->FromTable('Races')->WhereIn('Status', [RaceValue::StatusInit, RaceValue::StatusUpdate])
                ->OrderBy('RaceID', 'DESC')->Limit(100)->FetchAll();
        
        $accessor->ClearAll();
        $raceIDs = [];
        $racePlayerIDs = [];
        $userIDs = [];
        $racePool = RacePool::Instance();
        foreach($rows as $row){
            
            if($currentTime - $row->CreateTime < $finishTimelimit) continue;
            
            $raceIDs[] = $row->RaceID;
            if($row->RacePlayerIDs != null) $racePlayerIDs = array_merge($racePlayerIDs, array_values(get_object_vars(json_decode($row->RacePlayerIDs))));
            
            $racePool->Delete($row->RaceID);
        }
        
        if(empty($raceIDs)) return [
            'race' => $raceIDs,
            'currentTime' => $currentTime,
        ];
        
        $accessor->ClearAll();
        
        $rows = $accessor->FromTable('Users')->WhereIn('Race', $raceIDs)->FetchStyleAssoc()->FetchAll();
        $userIDs = array_column($rows, 'UserID');
        $accessor->Modify([
            'Race' => 0,
            'Lobby' => 0,
            'Room' => 0,
            'UpdateTime' => $currentTime,
        ]);
        
        $userPool = UserPool::Instance();
        foreach($userIDs as $userID) $userPool->Delete ($userID);
        
        $accessor->ClearCondition()
                ->FromTable('Races')->WhereIn('RaceID', $raceIDs)
                ->Modify([
                    'Status' => RaceValue::StatusFinish,
                    'UpdateTime' => $currentTime,
                ]);
        
        $racePlayerPool = RacePlayerPool::Instance();
        foreach($racePlayerIDs as $racePlayerID) $racePlayerPool->Delete ($racePlayerID);
        
        sort($raceIDs);
        sort($userIDs);
        sort($racePlayerIDs);
        
        return [
            'race' => $raceIDs,
            'user' => $userIDs,
            'racePlayer' => $racePlayerIDs,
            'currentTime' => $currentTime,
        ];
    }
}
