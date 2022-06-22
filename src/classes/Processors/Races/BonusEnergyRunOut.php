<?php

namespace Processors\Races;

use Consts\ErrorCode;
use Consts\Globals;
use Games\Consts\RaceValue;
use Games\Consts\SkillValue;
use Games\Exceptions\RaceException;
use Games\Players\PlayerHandler;
use Games\Races\RaceHandler;
use Games\Races\RacePlayerEffectHandler;
use Games\Races\RacePlayerHandler;
use Games\Races\RaceUtility;
use Games\Scenes\SceneHandler;
use Holders\ResultData;
/**
 * Description of BonusEnergyRunOut
 * 
 * 能量耗盡獎勵 CB2 版本
 * 能量耗盡為 0,0,0,0 才可使用，添加影響 S 值效果 5 秒。
 * 使用後能量扣除為負值，使此功能無法再次使用。
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class BonusEnergyRunOut extends BaseRace{
    
    public function Process(): ResultData {
        
        $raceHandler = new RaceHandler($this->userInfo->race);
        $raceInfo = $raceHandler->GetInfo();
        $racePlayerID = $raceInfo->racePlayers->{$this->userInfo->player};
        
        $racePlayerHandler = new RacePlayerHandler($racePlayerID);
        $racePlayerInfo = $racePlayerHandler->GetInfo();
        
        $remainingEnergy = array_sum($racePlayerInfo->energy);
        if($remainingEnergy > 0) throw new RaceException(RaceException::EnergyNotRunOut);
        if($remainingEnergy < 0) throw new RaceException(RaceException::EnergyNotEnough);

        $duration = 5;
        $currentTime = $GLOBALS[Globals::TIME_BEGIN];
        
        $binds[] = RaceUtility::BindRacePlayerEffect($racePlayerID, SkillValue::EffectS, 0.5, $GLOBALS[Globals::TIME_BEGIN], $currentTime + $duration);
        
        $racePlayerEffectHandler = new RacePlayerEffectHandler($racePlayerID);
        $racePlayerEffectHandler->AddAll($binds);
        
        $playerHandler = new PlayerHandler($this->userInfo->player);
        
        $playerHandler = RacePlayerEffectHandler::EffectPlayer($playerHandler, $racePlayerHandler);
        $raceHandler->SetPlayer($playerHandler);
        
        $sceneHandler = new SceneHandler($this->userInfo->scene);
        $raceHandler->SetSecne($sceneHandler);
        
        $racePlayerInfo = $racePlayerHandler->PayEnergy([1, 1, 1, 1]);
        
        $result = new ResultData(ErrorCode::Success);
        $result->h = $raceHandler->ValueH();
        $result->s = $raceHandler->ValueS();
        $result->hp = $racePlayerInfo->hp / RaceValue::DivisorHP;
        $result->duration = $duration;
        
        return $result;
    }
}
