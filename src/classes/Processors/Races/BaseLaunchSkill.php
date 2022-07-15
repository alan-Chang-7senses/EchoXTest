<?php

namespace Processors\Races;

use Consts\ErrorCode;
use Consts\Globals;
use Games\Consts\RaceValue;
use Games\Consts\SkillValue;
use Games\Exceptions\PlayerException;
use Games\Exceptions\RaceException;
use Games\Players\PlayerHandler;
use Games\Races\RaceHandler;
use Games\Races\RacePlayerEffectHandler;
use Games\Races\RacePlayerHandler;
use Games\Races\RacePlayerSkillHandler;
use Games\Races\RaceUtility;
use Games\Scenes\SceneHandler;
use Games\Skills\SkillHandler;
use Generators\ConfigGenerator;
use Helpers\InputHelper;
use Holders\ResultData;
/**
 * Description of BaseLaunchSkill
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
abstract class BaseLaunchSkill extends BaseRace{
    
    abstract function GetPlayerID() : int;
    
    public function Process(): ResultData {
        
        $skillID = InputHelper::post('id');
        $launchMax = (int)InputHelper::post('launchMax');
        if($launchMax != RaceValue::LaunchMaxYes) $launchMax = RaceValue::LaunchMaxNot;
        
        $playerSelf = $this->GetPlayerID();
        
        $playerHandlerSelf = new PlayerHandler($playerSelf);
        $playerInfo = $playerHandlerSelf->GetInfo();
        if(!$playerHandlerSelf->HasSkill($skillID)) throw new PlayerException(PlayerException::NoSuchSkill, ['[player]' => $playerInfo->id, '[skillID]' => $skillID]);
        
        $raceHandler = new RaceHandler($this->userInfo->race);
        $raceInfo = $raceHandler->GetInfo();
        
        $racePlayerIDSelf = $raceInfo->racePlayers->{$playerSelf};
        $racePlayerHandlerSelf = new RacePlayerHandler($racePlayerIDSelf);
        $racePlayerInfoSelf = $racePlayerHandlerSelf->GetInfo();
        
        $skillHandler = new SkillHandler($skillID);
        $skillInfo = $skillHandler->GetInfo();
        if(!$racePlayerHandlerSelf->EnoughEnergy($skillInfo->energy)) throw new RaceException(RaceException::EnergyNotEnough);
        
        $currentTime = $GLOBALS[Globals::TIME_BEGIN];
        $expireTime = $currentTime + ($skillInfo->duration == SkillValue::DurationForever ? RaceValue::ForeverAdditiveSec : $skillInfo->duration * $playerInfo->intelligent / RaceValue::DivisorSkillDuration);
        
        $skillHandler->playerHandler = $playerHandlerSelf;
        $skillHandler->racePlayerHandler = $racePlayerHandlerSelf;
        $effects = $skillHandler->GetEffects();
        
        $selfBinds = [];
        foreach($effects as $effect){

            $type = $effect['type'];
            $value = $effect['formulaValue'];
            
            if(in_array($type, RaceValue::PlayerEffectTypes)){
                $selfBinds[] = RaceUtility::BindRacePlayerEffect($racePlayerIDSelf, $type, $value, $currentTime, $expireTime);
            }elseif(in_array($type, RaceValue::PlayerEffectOnceType)){
                $selfBinds[] = RaceUtility::BindRacePlayerEffect($racePlayerIDSelf, $type, $value, $currentTime, $currentTime);
            }
        }
        
        $raceHandler->SetPlayer($playerHandlerSelf);
        $raceHandler->SetSecne(new SceneHandler($this->userInfo->scene));
        
        $otherBinds = [];
        $racePlayerHandlerOthers = [];
        $launchMaxResult = RaceValue::LaunchMaxFail;
        if($launchMax == RaceValue::LaunchMaxYes && $playerHandlerSelf->SkillLevel($skillID) == SkillValue::LevelMax && $raceHandler->LaunchMaxEffect($skillHandler)){
            
            $racePlayerHandlers = [];
            $racePlayerhandlerAll = [];
            foreach($raceInfo->racePlayers as $playerID => $racePlayerID){

                if($racePlayerID == $racePlayerIDSelf) continue;

                $racePlayerHandler = new RacePlayerHandler($racePlayerID);
                $racePlayerInfo = $racePlayerHandler->GetInfo();
                $racePlayerhandlerAll[$playerID] = $racePlayerHandler;

                match($racePlayerInfo->ranking){
                    $racePlayerInfoSelf->ranking + 1 => $racePlayerHandlers[SkillValue::TargetNext] = $racePlayerHandler,
                    ConfigGenerator::Instance()->AmountRacePlayerMax => $racePlayerHandlers[SkillValue::TargetLast] = $racePlayerHandler,
                    $racePlayerInfoSelf->ranking - 1 => $racePlayerHandlers[SkillValue::TargetPrevious] = $racePlayerHandler,
                    1 => $racePlayerHandlers[SkillValue::TargetFirst] = $racePlayerHandler,
                    default => null
                };

                $racePlayerHandlers[SkillValue::TargetOthers][] = $racePlayerHandler;
            }
            
            $maxEffects = $skillHandler->GetMaxEffects();
            foreach($maxEffects as $effect){
                
                $target = $effect['target'];
                $type = $effect['type'];
                $value = $effect['formulaValue'];
                
                if(isset(RaceValue::WeatherEffect[$type])){
                    $raceInfo = $raceHandler->SaveData(['weather' => RaceValue::WeatherEffect[$type]]);
                    $racePlayerHandlerOthers = $racePlayerhandlerAll;
                    continue;
                }
                
                if(isset(RaceValue::WindDirectionEffect[$type])){
                    $raceInfo = $raceHandler->SaveData(['windDirection' => RaceValue::WindDirectionEffect[$type]]);
                    $racePlayerHandlerOthers = $racePlayerhandlerAll;
                    continue;
                }
                
                $endTime = in_array($type, RaceValue::PlayerEffectOnceType) ? $currentTime : $expireTime;
                
                if($target == SkillValue::TargetSelf){
                    
                    $selfBinds[] = RaceUtility::BindRacePlayerEffect($racePlayerIDSelf, $type, $value, $currentTime, $endTime);
                
                }elseif($target == SkillValue::TargetOthers){
                    
                    foreach ($racePlayerHandlers[SkillValue::TargetOthers] as $handler) {
                        $info = $handler->GetInfo();
                        $endTime = $this->DeterminOtherTargetExpireTime($info,$skillInfo,$type,$expireTime);
                        $otherBinds[$info->id][] = RaceUtility::BindRacePlayerEffect($info->id, $type, $value, $currentTime, $endTime);
                        $racePlayerHandlerOthers[$info->player] = $handler;
                    }
                    
                }elseif(isset($racePlayerHandlers[$target])){
                    
                    $handler = $racePlayerHandlers[$target];
                    $info = $handler->GetInfo();
                    $endTime = $this->DeterminOtherTargetExpireTime($info,$skillInfo,$type,$expireTime);
                    $otherBinds[$info->id][] = RaceUtility::BindRacePlayerEffect($info->id, $type, $value, $currentTime, $endTime);
                    $racePlayerHandlerOthers[$info->player] = $handler;
                }
            }
            
            if($skillInfo->maxCondition == SkillValue::MaxConditionOffside) $racePlayerHandlerSelf->SaveData(['offside' => 0]);
            if($skillInfo->maxCondition == SkillValue::MaxConditionHit) $racePlayerHandlerSelf->SaveData(['hit' => 0]);
            
            $launchMaxResult = RaceValue::LaunchMaxSuccess;
        }
        
        if(!empty($selfBinds)) (new RacePlayerEffectHandler($racePlayerIDSelf))->AddAll($selfBinds);
        foreach($otherBinds as $racePlayerID => $binds) (new RacePlayerEffectHandler($racePlayerID))->AddAll($binds);
        
        (new RacePlayerSkillHandler($racePlayerIDSelf))->Add([
            'RacePlayerID' => $racePlayerIDSelf,
            'CreateTime' => $currentTime,
            'SkillID' => $skillID,
            'LaunchMax' => $launchMax,
            'Result' => $launchMaxResult
        ]);
        
        $racePlayerInfoSelf = $racePlayerHandlerSelf->PayEnergy($skillInfo->energy);
        $playerHandlerSelf = RacePlayerEffectHandler::EffectPlayer($playerHandlerSelf, $racePlayerHandlerSelf);
        $raceHandler->SetPlayer($playerHandlerSelf);
        $self = [
            'h' => $raceHandler->ValueH(),
            's' => $raceHandler->ValueS(),
            'hp' => $racePlayerInfoSelf->hp / RaceValue::DivisorHP,
            'energy' => $racePlayerInfoSelf->energy,
        ];
        
        $others = [];
        foreach($racePlayerHandlerOthers as $playerID => $racePlayerHandler){
            
            $playerHandler = new PlayerHandler($playerID);
            $playerHandler = RacePlayerEffectHandler::EffectPlayer($playerHandler, $racePlayerHandler);
            $raceHandler->SetPlayer($playerHandler);
            $racePlayerInfo = $racePlayerHandler->GetInfo();
            $others[] = [
                'id' => $playerID,
                'h' => $raceHandler->ValueH(),
                's' => $raceHandler->ValueS(),
                'hp' => $racePlayerInfo->hp / RaceValue::DivisorHP,
                'energy' => $racePlayerInfo->energy,
            ];
            
            $racePlayerHandler->SaveData(['hit' => $racePlayerHandler->GetInfo()->hit + 1]);
        }
        
        $result = new ResultData(ErrorCode::Success);
        $result->launchMax = $launchMax;
        $result->launchMaxResult = $launchMaxResult;
        $result->weather = $raceInfo->weather;
        $result->windDirection = $raceInfo->windDirection;
        $result->self = $self;
        $result->others = $others;
        
        return $result;
    }

    
    function DeterminOtherTargetExpireTime($info, $skillInfo, int $type, $endTime) : float{
        $currentTime = $GLOBALS[Globals::TIME_BEGIN];
        $interval  = $endTime - $currentTime;
        $playerHandler = new PlayerHandler($info->player);
        $playerIntelligent = $playerHandler->GetInfo()->intelligent;

        //Is skill normal duration
        return $skillInfo->duration != SkillValue::DurationForever || !in_array($type, RaceValue::PlayerEffectOnceType) ?
               $currentTime + ($interval  * RaceValue::DivisorSkillDurationForOther / $playerIntelligent) :
               $endTime; 
    }
}
