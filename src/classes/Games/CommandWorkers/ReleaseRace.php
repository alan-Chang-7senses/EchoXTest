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
        
        $config = ConfigGenerator::Instance();
        
        $finishTimelimit = $this->finish ?? $config->TimelimitRaceFinish;
        $currentTime = $GLOBALS[Globals::TIME_BEGIN];
        
        
        $accessor = new PDOAccessor(EnvVar::DBMain);
        
//        $rows = $accessor->FromTable('Races')->WhereIn('Status', [RaceValue::StatusInit, RaceValue::StatusUpdate])
//                ->OrderBy('RaceID', 'DESC')->Limit(100)->FetchAll();
        
        $rows = $accessor->executeBindFetchAll('SELECT Races.RaceID, Races.RacePlayerIDs, Races.CreateTime, RaceRooms.Lobby '
                . ' FROM Races LEFT JOIN RaceRooms USING(RaceID) WHERE Races.`Status` IN (0, 1) ORDER BY RaceID DESC LIMIT 100', []);

        $accessor->ClearAll();
        $raceIDs = [];
        $racePlayerIDs = [];
        $userIDs = [];
        $lobbyPlayerIDs = [];
        $playerIDsAll = [];
        foreach($rows as $row){
            
            if($currentTime - $row->CreateTime < $finishTimelimit)
                continue;
            
            $raceIDs[] = $row->RaceID;
            
            if($row->RacePlayerIDs === null)
                continue;
            
            $racePlayerIDsArr = get_object_vars(json_decode($row->RacePlayerIDs));
            $racePlayerIDs = array_merge($racePlayerIDs, array_values($racePlayerIDsArr));
            
            $playerIDs = array_keys($racePlayerIDsArr);
            $playerIDsAll = array_merge($playerIDsAll, $playerIDs);
            
            if(!isset($lobbyPlayerIDs[$row->Lobby])) $lobbyPlayerIDs[$row->Lobby] = [];
            $lobbyPlayerIDs[$row->Lobby] = array_merge($lobbyPlayerIDs[$row->Lobby], $playerIDs);
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
        
        $userPool = UserPool::Instance();
        foreach($userIDs as $userID) $userPool->Delete ($userID);
        
        $accessor->ClearCondition()
                ->FromTable('Races')->WhereIn('RaceID', $raceIDs)
                ->Modify([
                    'Status' => RaceValue::StatusRelease,
                    'UpdateTime' => $currentTime,
                ]);
        
        $racePool = RacePool::Instance();
        foreach($raceIDs as $raceID) $racePool->Delete($raceID);
        
        $racePlayerPool = RacePlayerPool::Instance();
        foreach($racePlayerIDs as $racePlayerID) $racePlayerPool->Delete ($racePlayerID);
        
        foreach($lobbyPlayerIDs as $lobby => $playerIDs) 
            RaceUtility::FinishRestoreLevel($lobby, $playerIDs);
        
        sort($raceIDs);
        sort($userIDs);
        sort($racePlayerIDs);
        sort($playerIDsAll);
        
        return [
            'race' => $raceIDs,
            'user' => $userIDs,
            'racePlayer' => $racePlayerIDs,
            'player' => $playerIDsAll,
            'currentTime' => $currentTime,
        ];
    }
}
