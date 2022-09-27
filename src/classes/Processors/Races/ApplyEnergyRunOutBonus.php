<?php

namespace Processors\Races;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\Globals;
use Games\Consts\EnergyRunOutBonus;
use Games\Consts\RaceValue;
use Games\Exceptions\RaceException;
use Games\Players\PlayerHandler;
use Games\Races\RaceHandler;
use Games\Races\RacePlayerEffectHandler;
use Games\Races\RacePlayerHandler;
use Games\Races\RaceUtility;
use Games\Scenes\SceneHandler;
use Holders\ResultData;

class ApplyEnergyRunOutBonus extends BaseRace{

   
    
    public function Process(): ResultData {
        
        $raceHandler = new RaceHandler($this->userInfo->race);
        $raceInfo = $raceHandler->GetInfo();
        $racePlayerID = $raceInfo->racePlayers->{$this->userInfo->player};
        
        $racePlayerHandler = new RacePlayerHandler($racePlayerID);
        $racePlayerInfo = $racePlayerHandler->GetInfo();
        
        $remainingEnergy = array_sum($racePlayerInfo->energy);
        if($remainingEnergy > 0) throw new RaceException(RaceException::EnergyNotRunOut);
        if($remainingEnergy < 0) throw new RaceException(RaceException::EnergyNotEnough);

        $accessor = new PDOAccessor(EnvVar::DBMain);
        $row = $accessor->FromTable('EnergyRunOutBonus')
                        ->WhereEqual('RacePlayerID',$racePlayerID)
                        ->Fetch();
        if($row === false) throw new RaceException(RaceException::EnergyRunOutBonusNotExist);

        foreach(EnergyRunOutBonus::RunOutRewards as $reward)
        {
            if($reward['number'] == $row->BonusID) $targetReward = $reward;
        }
        $currentTime = $GLOBALS[Globals::TIME_BEGIN];
        $binds[] = RaceUtility::BindRacePlayerEffect($racePlayerID, $targetReward['type'], $targetReward['value'],$currentTime , $currentTime + $targetReward['duration']);
        
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
        $result->duration = $targetReward['duration'];
        
        
        return $result;
    }
}
