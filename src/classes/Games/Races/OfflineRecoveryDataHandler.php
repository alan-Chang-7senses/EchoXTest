<?php

namespace Games\Races;

use Games\Accessors\RaceRecoveryDataAccessor;
class OfflineRecoveryDataHandler {

    public function SetRecoveryData(int $raceID,float $countDown,float $runTime,int $userID, int $moveDistance, int $skillID, int $skillCoolTime, int $normalSkillTime, int $fullLVSkillTime, int $skillID1, int $skillCoolTime1, int $normalSkillTime1, int $fullLVSkillTime1, int $skillID2, int $skillCoolTime2, int $normalSkillTime2, int $fullLVSkillTime2, int $skillID3, int $skillCoolTime3, int $normalSkillTime3, int $fullLVSkillTime3, int $skillID4, int $skillCoolTime4, int $normalSkillTime4, int $fullLVSkillTime4, int $skillID5, int $skillCoolTime5, int $normalSkillTime5, int $fullLVSkillTime5, int $createTime ) : void{
        $raceAccessor = new RaceRecoveryDataAccessor();
        $raceAccessor->SetRecoveryData( $raceID, $countDown, $runTime, $userID,  $moveDistance, $skillID, $skillCoolTime,  $normalSkillTime,  $fullLVSkillTime, $skillID1, $skillCoolTime1,  $normalSkillTime1,  $fullLVSkillTime1, $skillID2, $skillCoolTime2,  $normalSkillTime2,  $fullLVSkillTime2, $skillID3, $skillCoolTime3,  $normalSkillTime3,  $fullLVSkillTime3, $skillID4, $skillCoolTime4,  $normalSkillTime4,  $fullLVSkillTime4, $skillID5, $skillCoolTime5,  $normalSkillTime5,  $fullLVSkillTime5,  $createTime);
    }

    public function GetRecoveryData(int $playerID):array{
        $raceAccessor = new RaceRecoveryDataAccessor();
        return $raceAccessor->GetRecoveryData($playerID);
    }

}