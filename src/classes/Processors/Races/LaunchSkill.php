<?php

namespace Processors\Races;

use Consts\ErrorCode;
use Consts\Globals;
use Games\Consts\RaceValue;
use Games\Exceptions\PlayerException;
use Games\Exceptions\RaceException;
use Games\Players\PlayerHandler;
use Games\Races\RaceHandler;
use Games\Races\RacePlayerEffectHandler;
use Games\Races\RacePlayerHandler;
use Games\Races\RacePlayerSkillHandler;
use Games\Scenes\SceneHandler;
use Games\Skills\SkillHandler;
use Helpers\InputHelper;
use Holders\ResultData;
/**
 * Description of LaunchSkill
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class LaunchSkill extends BaseRace{
    
    public function Process(): ResultData {
        
        $skillID = InputHelper::post('id');
        
        $playerHandler = new PlayerHandler($this->userInfo->player);
        $playerInfo = $playerHandler->GetInfo();
        if(!$playerHandler->HasSkill($skillID)) throw new PlayerException(PlayerException::NoSuchSkill, ['[player]' => $playerInfo->id, '[skillID]' => $skillID]);
        
        $raceHandler = new RaceHandler($this->userInfo->race);
        $raceInfo = $raceHandler->GetInfo();
        
        $racePlayerID = $raceInfo->racePlayers->{$this->userInfo->player};
        $racePlayerHandler = new RacePlayerHandler($racePlayerID);
        
        $skillHandler = new SkillHandler($skillID);
        $skillInfo = $skillHandler->GetInfo();
        if(!$racePlayerHandler->EnoughEnergy($skillInfo->energy)) throw new RaceException(RaceException::EnergyNotEnough);
        
        $skillHandler->playerHandler = $playerHandler;
        $skillHandler->racePlayerHandler = $racePlayerHandler;
        
        $binds = [];
        $currentTime = $GLOBALS[Globals::TIME_BEGIN];
        $endTime = $currentTime + $skillInfo->duration;
        $effects = $skillHandler->GetEffects();
        foreach($effects as $effect){

            if(!in_array($effect['type'], RaceValue::PlayerEffectTypes)) continue;
            
            $binds[] = [
                'RacePlayerID' => $racePlayerID,
                'EffectType' => $effect['type'],
                'EffectValue' => $effect['formulaValue'],
                'StartTime' => $currentTime,
                'EndTime' => $endTime,
            ];
        }
        
        $racePlayerEffectHandler = new RacePlayerEffectHandler($racePlayerID);
        $racePlayerEffectHandler->AddAll($binds);
        
        $playerHandler = RacePlayerEffectHandler::EffectPlayer($playerHandler, $racePlayerHandler);
        
        (new RacePlayerSkillHandler($racePlayerID))->Add([
            'RacePlayerID' => $racePlayerID,
            'CreateTime' => $currentTime,
            'SkillID' => $skillID,
        ]);
        
        $raceHandler->SetPlayer($playerHandler);
        $raceHandler->SetSecne(new SceneHandler($this->userInfo->scene));
        
        $result = new ResultData(ErrorCode::Success);
        $result->valueS = $raceHandler->ValueS();
        $result->valueH = $raceHandler->ValueH();
        $result->info = $playerInfo;
        
        return $result;
    }
}
