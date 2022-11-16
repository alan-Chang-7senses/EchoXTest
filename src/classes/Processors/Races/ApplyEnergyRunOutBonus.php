<?php

namespace Processors\Races;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\Globals;
use Games\Consts\RaceValue;
use Games\Exceptions\RaceException;
use Games\Players\PlayerHandler;
use Games\Races\EnergyRunOutBonus;
use Games\Races\RaceHandler;
use Games\Races\RaceHP;
use Games\Races\RacePlayerEffectHandler;
use Games\Races\RacePlayerHandler;
use Games\Races\RaceUtility;
use Games\Races\RaceVerifyHandler;
use Games\Scenes\SceneHandler;
use Holders\ResultData;

class ApplyEnergyRunOutBonus extends BaseRace{

   
    
    public function Process(): ResultData {
        
        $raceHandler = new RaceHandler($this->userInfo->race);
        $raceInfo = $raceHandler->GetInfo();
        // $racePlayerID = $raceInfo->racePlayers->{$this->userInfo->player};
        $racePlayerID = $this->GetRacePlayerID();

        $racePlayerHandler = new RacePlayerHandler($racePlayerID);
        $racePlayerInfo = $racePlayerHandler->GetInfo();
        

        $accessor = new PDOAccessor(EnvVar::DBMain);
        $row = $accessor->executeBindFetch('SELECT BonusID, Achievement
            FROM EnergyRunOutBonus t1, PlayerNFT t2
            WHERE t1.RacePlayerID = :racePlayerID
            AND t2.PlayerID = :playerID;',['racePlayerID' => $racePlayerID, 'playerID' => $racePlayerInfo->player]);                        
        if($row === false) throw new RaceException(RaceException::EnergyRunOutBonusNotExist);



        foreach((new EnergyRunOutBonus($row->Achievement))->GetRunOutBonus() as $reward)
        {
            if($reward['number'] == $row->BonusID) $targetReward = $reward;
        }
        $currentTime = $GLOBALS[Globals::TIME_BEGIN];
        $binds[] = RaceUtility::BindRacePlayerEffect($racePlayerID, $targetReward['type'], $targetReward['value'],$currentTime , $currentTime + $targetReward['duration']);
        
        $racePlayerEffectHandler = new RacePlayerEffectHandler($racePlayerID);
        $racePlayerEffectHandler->AddAll($binds);
        
        $playerHandler = new PlayerHandler($racePlayerInfo->player);
        
        $playerHandler = RacePlayerEffectHandler::EffectPlayer($playerHandler, $racePlayerHandler);
        $raceHandler->SetPlayer($playerHandler);
        
        $sceneHandler = new SceneHandler($this->userInfo->scene);
        $raceHandler->SetSecne($sceneHandler);
        


        
        $result = new ResultData(ErrorCode::Success);
        $result->h = $raceHandler->ValueH();
        $result->s = $raceHandler->ValueS();
        $hp = RaceHP::Instance()->UpdateHP($racePlayerID,$result->h);
        $result->hp = $hp / RaceValue::DivisorHP;
        
        RaceVerifyHandler::Instance()->EnergyBonus($racePlayerInfo->id, $result->s);
        
        $result->duration = $targetReward['duration'];
        return $result;
    }
}
