<?php

namespace Processors\Races;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\Globals;
use Games\Consts\EnergyRunOutBonus;
use Games\Exceptions\RaceException;
use Games\Races\RaceHandler;
use Games\Races\RacePlayerHandler;
use Holders\ResultData;
/**
 * Description of BonusEnergyRunOut
 * 
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

        $effectType = 0;
        $effectValue = 0;
        $number = 0;
        $r = rand(1,100);
        foreach(EnergyRunOutBonus::RunOutRewards as $rewardEffect)
        {
            $r -= $rewardEffect['proportion'];
            if($r <= 0)
            {
                $effectType = $rewardEffect['type'];
                $effectValue = $rewardEffect['value'];
                $number = $rewardEffect['number'];
                break;
            }
        }

        $accessor = new PDOAccessor(EnvVar::DBMain);
        $accessor->FromTable('EnergyRunOutBonus')
                ->Add(['RacePlayerID' =>$racePlayerID,'BonusID' => $number, 'UpdateTime' => $GLOBALS[Globals::TIME_BEGIN]],true);

        
        $result = new ResultData(ErrorCode::Success);
        
        $result->effect = $effectType;
        $result->effectValue = $effectValue;
        $result->number = $number;
        
        return $result;
    }
}
