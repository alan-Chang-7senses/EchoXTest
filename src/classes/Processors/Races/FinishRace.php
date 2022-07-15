<?php

namespace Processors\Races;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\Globals;
use Games\Consts\RaceValue;
use Games\Exceptions\RaceException;
use Games\Pools\UserPool;
use Games\Races\RaceHandler;
use Generators\ConfigGenerator;
use Generators\DataGenerator;
use Helpers\InputHelper;
use Holders\ResultData;
/**
 * Description of FinishRace
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class FinishRace extends BaseRace{
    
    public function Process(): ResultData {
        
        $players = json_decode(InputHelper::post('players'));
        if(!is_array($players) || count($players) > ConfigGenerator::Instance()->AmountRacePlayerMax) throw new RaceException(RaceException::IncorrectPlayerNumber);
        DataGenerator::ExistProperties($players[0], ['id', 'ranking']);
        
        $rankings = [];
        foreach($players as $player){
            $rankings[$player->id] = $player->ranking;
        }
        
        $raceHandler = new RaceHandler($this->userInfo->race);
        $raceInfo = $raceHandler->GetInfo();
        if($raceInfo->status == RaceValue::StatusFinish) throw new RaceException(RaceException::Finished);
        
        $userPool = UserPool::Instance();
        $accessor = new PDOAccessor(EnvVar::DBMain);
        $accessor->FromTable('RacePlayer');
        $users = [];
        foreach ($raceInfo->racePlayers as $racePlayerID) {
            
            $row = $accessor->WhereEqual('RacePlayerID', $racePlayerID)->Fetch();
            if($row->Status != RaceValue::StatusReach) throw new RaceException(RaceException::PlayerNotReached, ['[player]' => $row->PlayerID]);
            
            if(!isset($rankings[$row->PlayerID]) || $rankings[$row->PlayerID] != $row->Ranking){
                throw new RaceException(RaceException::RankingNoMatch, [
                    '[player]' => $row->PlayerID,
                    '[front]' => $rankings[$row->PlayerID] ?? 0,
                    '[back]' => $row->Ranking,
                ]);
            }
            
            $users[] = [
                'id' => $row->UserID,
                'player' => $row->PlayerID,
                'ranking' => $row->Ranking,
                'duration' => $row->FinishTime - $row->CreateTime,
            ];
            
            $userPool->Delete($row->UserID);
            $accessor->ClearCondition();
        }
        
//        $raceHandler->Finish();
        $raceID = $raceInfo->id;
        $accessor->Transaction(function() use ($accessor, $raceID){
            
            $currentTime = $GLOBALS[Globals::TIME_BEGIN];
            $accessor->WhereEqual('RaceID', $raceID)->Modify([
                'Status' => RaceValue::StatusFinish,
                'UpdateTime' => $currentTime,
            ]);
            
            $accessor->ClearCondition()->FromTable('Users')->WhereEqual('Race', $raceID, 'RaceID')->Modify([
                'Race' => RaceValue::NotInRace,
                'UpdateTime' => $currentTime,
            ]);
            
            $accessor->ClearCondition()->FromTable('Races')->WhereEqual('RaceID', $raceID)->Modify([
                'Status' => RaceValue::StatusFinish,
                'UpdateTime' => $currentTime,
                'FinishTime' => $currentTime,
            ]);
        });
        
        $result = new ResultData(ErrorCode::Success);
        $result->users = $users;
        return $result;
    }
}
