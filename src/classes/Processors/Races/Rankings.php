<?php

namespace Processors\Races;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\Globals;
use Games\Consts\RaceValue;
use Games\Exceptions\RaceException;
use Games\Races\RaceHandler;
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
        foreach ($players as $player){
            if(!property_exists($raceInfo->racePlayers, $player->id)) throw new RaceException (RaceException::PlayerNotInThisRace);
            $rankings[$raceInfo->racePlayers->{$player->id}] = $player->ranking;
        }
        
        $accessor = new PDOAccessor(EnvVar::DBMain);
        $accessor->Transaction(function() use ($accessor, $rankings){
            
            $rows = $accessor->FromTable('RacePlayer')->WhereIn('RacePlayerID', array_keys($rankings))->FetchAll();
            foreach($rows as $row){
                
                if($row->Status == RaceValue::StatusReach) continue;
                $accessor->ClearCondition();
                $accessor->FromTable('RacePlayer')->WhereEqual('RacePlayerID', $row->RacePlayerID)->Modify([
                    'Ranking' => $rankings[$row->RacePlayerID],
                    'Status' => RaceValue::StatusUpdate,
                    'UpdateTime' => $GLOBALS[Globals::TIME_BEGIN]
                ]);
            }
        });
        
        $result = new ResultData(ErrorCode::Success);
        return $result;
    }
}
