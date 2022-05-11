<?php

namespace Processors\Races;

use Consts\ErrorCode;
use Games\Exceptions\RaceException;
use Games\Races\RaceHandler;
use Games\Races\RacePlayerHandler;
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
        $racePlayerHandlers = [];
        foreach($positions as $position){
            $racePlayerID = $raceInfo->racePlayers->{$position->player} ?? null;
            if($racePlayerID === null) throw new RaceException(RaceException::PlayerNotInThisRace);
            $racePlayerHandlers[$position->player] = new RacePlayerHandler($racePlayerID);
        }
        
        foreach ($positions as $position) {
            $racePlayerHandlers[$position->player]->SaveData(['position' => $position->position]);
        }
        
        $result = new ResultData(ErrorCode::Success);
        return $result;
    }
}
