<?php

namespace Processors\Races;


use Consts\ErrorCode;
use Consts\Globals;
use Holders\ResultData;
use Generators\DataGenerator;
use Helpers\InputHelper;
use Games\Races\OfflineRecoveryDataHandler;


class OfflineRecoveryData extends BaseRace {
    
    public function Process(): ResultData {


        $raceID = InputHelper::post('raceID');
        $countDown = InputHelper::post('countDown');
        $runTime = InputHelper::post('runTime');
        $playersData = json_decode(InputHelper::post('playersData'));
        DataGenerator::ExistProperties($playersData[0], ['playerID', 'moveDistance','skillData']);
        


        $offlineRecoveryDataHandler = new OfflineRecoveryDataHandler();
        for($i = 0; $i<count($playersData);$i++)
        {
            $offlineRecoveryDataHandler->SetRecoveryData($raceID,$countDown,$runTime,$playersData[$i]->playerID,$playersData[$i]->moveDistance,
            $playersData[$i]->skillData[0]->skillID,$playersData[$i]->skillData[0]->skillCoolTime,$playersData[$i]->skillData[0]->normalSkillTime,$playersData[$i]->skillData[0]->fullLVSkillTime,
            $playersData[$i]->skillData[1]->skillID,$playersData[$i]->skillData[1]->skillCoolTime,$playersData[$i]->skillData[1]->normalSkillTime,$playersData[$i]->skillData[1]->fullLVSkillTime,
            $playersData[$i]->skillData[2]->skillID,$playersData[$i]->skillData[2]->skillCoolTime,$playersData[$i]->skillData[2]->normalSkillTime,$playersData[$i]->skillData[2]->fullLVSkillTime,
            $playersData[$i]->skillData[3]->skillID,$playersData[$i]->skillData[3]->skillCoolTime,$playersData[$i]->skillData[3]->normalSkillTime,$playersData[$i]->skillData[3]->fullLVSkillTime,
            $playersData[$i]->skillData[4]->skillID,$playersData[$i]->skillData[4]->skillCoolTime,$playersData[$i]->skillData[4]->normalSkillTime,$playersData[$i]->skillData[4]->fullLVSkillTime,
            $playersData[$i]->skillData[5]->skillID,$playersData[$i]->skillData[5]->skillCoolTime,$playersData[$i]->skillData[5]->normalSkillTime,$playersData[$i]->skillData[5]->fullLVSkillTime,
            $GLOBALS[Globals::TIME_BEGIN]);
          
        }

        $result = new ResultData(ErrorCode::Success);
        
        return $result;
    }
}
