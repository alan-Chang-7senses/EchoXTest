<?php

namespace Processors\Races;

use Consts\ErrorCode;
use Games\Consts\RaceValue;
use Games\Consts\SkillValue;
use Games\Players\PlayerHandler;
use Games\Players\PlayerUtility;
use Games\Races\RaceHandler;
use Games\Races\RacePlayerEffectHandler;
use Games\Races\RacePlayerHandler;
use Games\Scenes\SceneHandler;
use Games\Skills\SkillHandler;
use Holders\ResultData;
/**
 * Description of OfflineRecovery
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class OfflineRecovery extends BaseRace {
    
    public function Process(): ResultData {
        
        $sceneHandler = new SceneHandler($this->userInfo->scene);
        $sceneInfo = $sceneHandler->GetInfo();
        $climate = $sceneHandler->GetClimate();
        
        $raceHandler = new RaceHandler($this->userInfo->race);
        $raceHandler->SetSecne($sceneHandler);
        $raceInfo = $raceHandler->GetInfo();
        
        $scene = [
            'env' => $sceneInfo->env,
            'weather' => $raceInfo->weather,
            'windDirection' => $raceInfo->windDirection,
            'windSpeed' => $climate->windSpeed,
            'ligthing' => $climate->lighting,
        ];
        
        $players = [];
        foreach($raceInfo->racePlayers as $playerID => $racePlayerID){
            
            $playerHandler = new PlayerHandler($playerID);
            $racePlayerHandler = new RacePlayerHandler($racePlayerID);
            
            $playerHandler = RacePlayerEffectHandler::EffectPlayer($playerHandler, $racePlayerHandler);
            $playerInfo = $playerHandler->GetInfo();
            $racePlayerHandler = $raceHandler->SetPlayer($playerHandler);
            $racePlayerInfo = $racePlayerHandler->GetInfo();
            
            $skills = [];
            foreach($playerInfo->skills as $playerSkill){
                
                if($playerSkill->slot == SkillValue::NotInSlot) continue;
                
                $skillHandler = new SkillHandler($playerSkill->id);
                $skillInfo = $skillHandler->GetInfo();
                $skills[] = [
                    'id' => $skillInfo->id,
                    'name' => $skillInfo->name,
                    'level' => $playerSkill->level,
                    'slot' => $playerSkill->slot,
                    'energy' => $skillInfo->energy,
                    'cooldown' => $skillInfo->cooldown,
                    'duration' => $skillInfo->duration,
                    'maxCondition' => $skillInfo->maxCondition,
                    'maxConditionValue' => $skillInfo->maxConditionValue,
                    'effects' => $skillHandler->GetEffects(),
                    'maxEffects' => $skillHandler->GetMaxEffects(),
                ];
            }
            
            $players[] = [
                'user' => $racePlayerInfo->user,
                'player' => $racePlayerInfo->player,
                'energy' => $racePlayerInfo->energy,
                'hp' => $racePlayerInfo->hp / RaceValue::DivisorHP,
                's' => $raceHandler->ValueS(),
                'h' => $raceHandler->ValueH(),
                'position' => $racePlayerInfo->position,
                'parts' => [
                    'head' => PlayerUtility::PartCodeByDNA($playerInfo->dna->head),
                    'body' => PlayerUtility::PartCodeByDNA($playerInfo->dna->body),
                    'hand' => PlayerUtility::PartCodeByDNA($playerInfo->dna->hand),
                    'leg' => PlayerUtility::PartCodeByDNA($playerInfo->dna->leg),
                    'back' => PlayerUtility::PartCodeByDNA($playerInfo->dna->back),
                    'hat' => PlayerUtility::PartCodeByDNA($playerInfo->dna->hat),
                ],
                'skills' => $skills,
            ];
        }
        
        $result = new ResultData(ErrorCode::Success);
        $result->user = $this->userInfo->id;
        $result->player = $this->userInfo->player;
        $result->scene = $scene;
        $result->players = $players;
        
        return $result;
    }
}
