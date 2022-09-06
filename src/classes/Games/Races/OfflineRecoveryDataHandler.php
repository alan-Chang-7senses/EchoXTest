<?php

namespace Games\Races;

use Games\Accessors\RaceRecoveryDataAccessor;
class OfflineRecoveryDataHandler {

    public function SetRecoveryData(int $raceID,float $countDown,float $runTime,int $userID, float $moveDistance, int $skillID, float $skillCoolTime, float $normalSkillTime, float $fullLVSkillTime, int $skillID1, float $skillCoolTime1, float $normalSkillTime1, float $fullLVSkillTime1, int $skillID2, float $skillCoolTime2, float $normalSkillTime2, float $fullLVSkillTime2, int $skillID3, float $skillCoolTime3, float $normalSkillTime3, float $fullLVSkillTime3, int $skillID4, float $skillCoolTime4, float $normalSkillTime4, float $fullLVSkillTime4, int $skillID5, float $skillCoolTime5, float $normalSkillTime5, float $fullLVSkillTime5, int|float $createTime ) : void{
        $raceAccessor = new RaceRecoveryDataAccessor();
        $raceAccessor->SetRecoveryData( $raceID, $countDown, $runTime, $userID,  $moveDistance, $skillID, $skillCoolTime,  $normalSkillTime,  $fullLVSkillTime, $skillID1, $skillCoolTime1,  $normalSkillTime1,  $fullLVSkillTime1, $skillID2, $skillCoolTime2,  $normalSkillTime2,  $fullLVSkillTime2, $skillID3, $skillCoolTime3,  $normalSkillTime3,  $fullLVSkillTime3, $skillID4, $skillCoolTime4,  $normalSkillTime4,  $fullLVSkillTime4, $skillID5, $skillCoolTime5,  $normalSkillTime5,  $fullLVSkillTime5,  $createTime);
    }

    public function GetRecoveryData(int $playerID):mixed{
        $raceAccessor = new RaceRecoveryDataAccessor();
        return $raceAccessor->GetRecoveryData($playerID);
    }

}