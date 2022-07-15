<?php

namespace Processors\Races;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Games\Exceptions\RaceException;
use Games\Races\RaceHandler;
use Generators\ConfigGenerator;
use Generators\DataGenerator;
use Helpers\InputHelper;
use Holders\ResultData;
/**
 * Description of RecordPositions
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class RecordPositions extends BaseRace{
    
    public function Process(): ResultData {
        
        $positions = InputHelper::post('positions');
        $positions = json_decode($positions);
        if(!is_array($positions) || count($positions) > ConfigGenerator::Instance()->AmountRacePlayerMax) throw new RaceException(RaceException::IncorrectPlayerNumber);
        DataGenerator::ExistProperties($positions[0], ['player', 'position']);
        
        $raceHandler = new RaceHandler($this->userInfo->race);
        $raceInfo = $raceHandler->GetInfo();
        $racePlayerPositions = [];
        foreach($positions as $position){
            $racePlayerID = $raceInfo->racePlayers->{$position->player} ?? null;
            if($racePlayerID === null) throw new RaceException(RaceException::PlayerNotInThisRace);
            $racePlayerPositions[$racePlayerID] = $position->position;
        }
        
        $accessor = new PDOAccessor(EnvVar::DBMain);
        $accessor->Trasaction(function() use ($accessor, $racePlayerPositions){
            
            $accessor->FromTable('RacePlayer')->WhereIn('RacePlayerID', array_keys($racePlayerPositions))->ForUpdate()->FetchAll();
            
            foreach($racePlayerPositions as $racePlayerID => $position){
                $accessor->ClearCondition();
                $accessor->FromTable('RacePlayer')->WhereEqual('RacePlayerID', $racePlayerID)->Modify(['Position' => $position]);
            }
        });
        
        $result = new ResultData(ErrorCode::Success);
        return $result;
    }
}
