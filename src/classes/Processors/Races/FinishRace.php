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
        
        $users = [];
        foreach ($raceInfo->racePlayers as $racePlayerID) {
            
            $racePlayerInfo = (new RacePlayerHandler($racePlayerID))->GetInfo();
            if($racePlayerInfo->status != RaceValue::StatusReach) throw new RaceException(RaceException::PlayerNotReached, ['[player]' => $racePlayerInfo->player]);
            
            if(!isset($rankings[$racePlayerInfo->player]) || $rankings[$racePlayerInfo->player] != $racePlayerInfo->ranking){
                throw new RaceException(RaceException::RankingNoMatch, [
                    '[player]' => $racePlayerInfo->player,
                    '[front]' => $rankings[$racePlayerInfo->player] ?? 0,
                    '[back]' => $racePlayerInfo->ranking,
                ]);
            }
            
            $users[] = [
                'id' => $racePlayerInfo->user,
                'player' => $racePlayerInfo->player,
                'ranking' => $racePlayerInfo->ranking,
                'duration' => $racePlayerInfo->finishTime - $racePlayerInfo->createTime,
            ];
        }
        
        $raceHandler->Finish();
        
        $result = new ResultData(ErrorCode::Success);
        $result->users = $users;
        return $result;
    }
}
