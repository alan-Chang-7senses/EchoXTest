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

    const RunOutRewards = 
    [
        [
            'number' => 2,
            'proportion' => 10,
            'duration' => 10,
            'type' => SkillValue::EffectS,
            'value' => 5,
        ],
        [
            'number' => 3,
            'proportion' => 20,
            'duration' => 0,
            'type' => SkillValue::EffectHP,
            'value' => 35,
        ],
        [
            'number' => 1,
            'proportion' => 40,
            'duration' => 10,
            'type' => SkillValue::EffectS,
            'value' => 1.5,
        ],
        [
            'number' => 4,
            'proportion' => 30,
            'duration' => 20,
            'type' => SkillValue::EffectH,
            'value' => -0.5,
        ],
    ];    
    
    public function Process(): ResultData {
        
        $raceHandler = new RaceHandler($this->userInfo->race);
        $raceInfo = $raceHandler->GetInfo();
        $racePlayerID = $raceInfo->racePlayers->{$this->userInfo->player};
        
        $racePlayerHandler = new RacePlayerHandler($racePlayerID);
        $racePlayerInfo = $racePlayerHandler->GetInfo();
        
        $remainingEnergy = array_sum($racePlayerInfo->energy);
        if($remainingEnergy > 0) throw new RaceException(RaceException::EnergyNotRunOut);
        if($remainingEnergy < 0) throw new RaceException(RaceException::EnergyNotEnough);

        $duration = 0;
        $effectType = 0;
        $effectValue = 0;
        $number = 0;
        $currentTime = $GLOBALS[Globals::TIME_BEGIN];
        $r = rand(1,100);
        foreach(self::RunOutRewards as $rewardEffect)
        {
            $r -= $rewardEffect['proportion'];
            if($r <= 0)
            {
                $duration = $rewardEffect['duration'];
                $effectType = $rewardEffect['type'];
                $effectValue = $rewardEffect['value'];
                $number = $rewardEffect['number'];
                break;
            }
        }

        
        $binds[] = RaceUtility::BindRacePlayerEffect($racePlayerID, $effectType, $effectValue, $GLOBALS[Globals::TIME_BEGIN], $currentTime + $duration);
        
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
        
        $result->effect = $effectType;
        $result->effectValue = $effectValue;
        $result->number = $number;
        
        return $result;
    }
}
