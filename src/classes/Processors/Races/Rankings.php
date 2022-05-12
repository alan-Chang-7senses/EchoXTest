<?php

namespace Processors\Races;

use Consts\ErrorCode;
use Games\Consts\RaceValue;
use Games\Exceptions\RaceException;
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
        foreach ($players as $player){
            if(!property_exists($raceInfo->racePlayers, $player->id)) throw new RaceException (RaceException::PlayerNotInThisRace);
            $rankings[$player->id] = $player->ranking;
        }
        
        foreach ($raceInfo->racePlayers as $racePlayerID) {

            $racePlayerHandler = new RacePlayerHandler($racePlayerID);
            $racePlayerInfo = $racePlayerHandler->GetInfo();
            if($racePlayerInfo->status == RaceValue::StatusReach) continue;
            
            $racePlayerHandler->SaveData(['ranking' => $rankings[$racePlayerInfo->player]]);
        }
        
        $result = new ResultData(ErrorCode::Success);
        return $result;
    }
}
