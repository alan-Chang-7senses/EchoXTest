<?php

namespace Processors\Races;

use Consts\ErrorCode;
use Games\Consts\RaceValue;
use Games\Consts\SkillValue;
use Games\Players\PlayerHandler;
use Games\Players\PlayerUtility;
use Games\Races\OfflineRecoveryDataHandler;
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
            'lighting' => $climate->lighting,
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
                    'icon' => $skillInfo->icon,
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
            
            $recoveryDataHandler = new OfflineRecoveryDataHandler();
            $recoveryDataArray = $recoveryDataHandler->GetRecoveryData($racePlayerInfo->player);
            $skillData = [];
            for($i = 1; $i < 7; $i++){
                $skillData[] = [ 
                    'skillID' =>$recoveryDataArray->{'SkillID'.$i},
                    'skillCoolTime' =>$recoveryDataArray->{'SkillCoolTime'.$i},
                    'normalSkillTime' =>$recoveryDataArray->{'NormalSkillTime'.$i},
                    'fullLVSkillTime' =>$recoveryDataArray->{'FullLVSkillTime'.$i},
                ];
            }
    
            $recoveryDataArray = [
                'raceID' => $recoveryDataArray->RaceID,
                'countDown' => $recoveryDataArray->CountDown,
                'runTime' => $recoveryDataArray->RunTime,
                'playerID' => $recoveryDataArray->PlayerID,
                'moveDistance' => $recoveryDataArray->MoveDistance,
                'skillData' => $skillData,
                'createTime' =>$recoveryDataArray->CreateTime,
            ];
            
            $players[] = [
                'user' => $racePlayerInfo->user,
                'player' => $racePlayerInfo->player,
                'energy' => $racePlayerInfo->energy,
                'hp' => $racePlayerInfo->hp / RaceValue::DivisorHP,
                's' => $raceHandler->ValueS(),
                'h' => $raceHandler->ValueH(),
                'position' => $racePlayerInfo->position,
                'parts' => PlayerUtility::PartCodes($playerInfo),
                'skills' => $skills,
                'recovery' => $recoveryDataArray,
            ];
        }

        $result = new ResultData(ErrorCode::Success);
        $result->user = $this->userInfo->id;
        $result->player = $this->GetCurrentPlayerID();
        $result->scene = $scene;
        $result->players = $players;
        
        return $result;
    }
}
