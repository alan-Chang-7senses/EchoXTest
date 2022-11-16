<?php

namespace Processors\Races;

use Consts\ErrorCode;
use Games\Consts\RaceValue;
use Games\Exceptions\RaceException;
use Games\Players\PlayerHandler;
use Games\Races\RaceHandler;
use Games\Races\RacePlayerEffectHandler;
use Games\Races\RacePlayerHandler;
use Games\Races\RaceUtility;
use Games\Races\RaceVerifyHandler;
use Holders\ResultData;
/**
 * Description of BonusEnergyAgain
 *
 * 競賽中的能量再獲得
 * 限制獲得次數
 * 角色 技能影響後 的耐力值決定獲得的能量總數量。
 * 
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class EnergyAgain extends BaseRace{
    
    public function Process(): ResultData {
        
        $raceHandler = new RaceHandler($this->userInfo->race);
        $raceInfo = $raceHandler->GetInfo();
        
        // $racePlayerHandler = new RacePlayerHandler($raceInfo->racePlayers->{$this->userInfo->player});
        $racePlayerHandler = new RacePlayerHandler($this->GetRacePlayerID());
        $racePlayerInfo = $racePlayerHandler->GetInfo();
        
        if($racePlayerInfo->energyAgain >= RaceValue::EnergyAgainCount) throw new RaceException(RaceException::EnergyAgainOver);
        
        // $playerHandler = new PlayerHandler($this->userInfo->player);
        $playerHandler = new PlayerHandler($racePlayerInfo->player);
        $playerHandler = RacePlayerEffectHandler::EffectPlayer($playerHandler, $racePlayerHandler);
        
        $playerInfo = $playerHandler->GetInfo();
        //暫時無裝備、成就系統。
        $energy = RaceUtility::RandomEnergyAgain(count($playerInfo->skillHole),0);
        
        $racePlayerHandler->SaveData(['energy' => $energy, 'energyAgain' => $racePlayerInfo->energyAgain + 1]);
        
        $result = new ResultData(ErrorCode::Success);
        $result->energy = $energy;
                      
        RaceVerifyHandler::Instance()->EnergyAgain($racePlayerInfo->id);
                
        return $result;
    }
}
