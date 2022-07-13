<?php

namespace Games\Accessors; 

use PDO;

class RaceRecoveryDataAccessor extends BaseAccessor{
    
    public function SetRecoveryData(int $raceID,float $countDown,float $runTime,int $userID, int $moveDistance,int $skillID, int $skillCoolTime, int $normalSkillTime, int $fullLVSkillTime,int $skillID1, int $skillCoolTime1, int $normalSkillTime1, int $fullLVSkillTime1,int $skillID2, int $skillCoolTime2, int $normalSkillTime2, int $fullLVSkillTime2,int $skillID3, int $skillCoolTime3, int $normalSkillTime3, int $fullLVSkillTime3,int $skillID4, int $skillCoolTime4, int $normalSkillTime4, int $fullLVSkillTime4,int $skillID5, int $skillCoolTime5, int $normalSkillTime5, int $fullLVSkillTime5, int $createTime) : bool{
        /*return $this->MainAccessor()->FromTable('RecoveryData')->Add($bind);*/
        $accessor = $this->MainAccessor();
        return $accessor->executeBind('INSERT INTO `RecoveryData` VALUES (:raceID, :countDown, :runTime, :userID, :moveDistance, '
        .':skillID, :skillCoolTime, :normalSkillTime, :fullLVSkillTime, '
        .':skillID1, :skillCoolTime1, :normalSkillTime1, :fullLVSkillTime1,'
        .':skillID2, :skillCoolTime2, :normalSkillTime2, :fullLVSkillTime2,'
        .':skillID3, :skillCoolTime3, :normalSkillTime3, :fullLVSkillTime3,'
        .':skillID4, :skillCoolTime4, :normalSkillTime4, :fullLVSkillTime4,'
        .':skillID5, :skillCoolTime5, :normalSkillTime5, :fullLVSkillTime5,'
        .':createTime) ON DUPLICATE KEY UPDATE `RaceID` = :raceID2,`CountDown` = :countDown2,`RunTime` = :runTime2,`MoveDistance` = :moveDistance2,'
        .'`SkillID1` = :skillID10,`SkillCoolTime1` = :skillCoolTime10,`NormalSkillTime1` = :normalSkillTime10,`FullLVSkillTime1` = :fullLVSkillTime10,'
        .'`SkillID2` = :skillID11,`SkillCoolTime2` = :skillCoolTime11,`NormalSkillTime2` = :normalSkillTime11,`FullLVSkillTime2` = :fullLVSkillTime11, '
        .'`SkillID3` = :skillID12,`SkillCoolTime3` = :skillCoolTime12,`NormalSkillTime3` = :normalSkillTime12,`FullLVSkillTime3` = :fullLVSkillTime12,'
        .'`SkillID4` = :skillID13,`SkillCoolTime4` = :skillCoolTime13,`NormalSkillTime4` = :normalSkillTime13,`FullLVSkillTime4` = :fullLVSkillTime13,'
        .'`SkillID5` = :skillID14,`SkillCoolTime5` = :skillCoolTime14,`NormalSkillTime5` = :normalSkillTime14,`FullLVSkillTime5` = :fullLVSkillTime14,'
        .'`SkillID6` = :skillID15,`SkillCoolTime6` = :skillCoolTime15,`NormalSkillTime6` = :normalSkillTime15,`FullLVSkillTime6` = :fullLVSkillTime15,'
        .'`CreateTime` = :createTime2', [
            'raceID' => $raceID,
            'countDown' => $countDown,
            'runTime' => $runTime,
            'userID' => $userID,
            'moveDistance' => $moveDistance,
            'skillID' => $skillID,
            'skillCoolTime' => $skillCoolTime,
            'normalSkillTime' => $normalSkillTime,
            'fullLVSkillTime' => $fullLVSkillTime,
            'skillID1' => $skillID1,
            'skillCoolTime1' => $skillCoolTime1,
            'normalSkillTime1' => $normalSkillTime1,
            'fullLVSkillTime1' => $fullLVSkillTime1,
            'skillID2' => $skillID2,
            'skillCoolTime2' => $skillCoolTime2,
            'normalSkillTime2' => $normalSkillTime2,
            'fullLVSkillTime2' => $fullLVSkillTime2,
            'skillID3' => $skillID3,
            'skillCoolTime3' => $skillCoolTime3,
            'normalSkillTime3' => $normalSkillTime3,
            'fullLVSkillTime3' => $fullLVSkillTime3,
            'skillID4' => $skillID4,
            'skillCoolTime4' => $skillCoolTime4,
            'normalSkillTime4' => $normalSkillTime4,
            'fullLVSkillTime4' => $fullLVSkillTime4,
            'skillID5' => $skillID5,
            'skillCoolTime5' => $skillCoolTime5,
            'normalSkillTime5' => $normalSkillTime5,
            'fullLVSkillTime5' => $fullLVSkillTime5,
            'createTime' => $createTime,



            'raceID2' => $raceID,
            'countDown2' => $countDown,
            'runTime2' => $runTime,
            'moveDistance2' => $moveDistance,
            'skillID10' => $skillID,
            'skillCoolTime10' => $skillCoolTime,
            'normalSkillTime10' => $normalSkillTime,
            'fullLVSkillTime10' => $fullLVSkillTime,
            'skillID11' => $skillID1,
            'skillCoolTime11' => $skillCoolTime1,
            'normalSkillTime11' => $normalSkillTime1,
            'fullLVSkillTime11' => $fullLVSkillTime1,
            'skillID12' => $skillID2,
            'skillCoolTime12' => $skillCoolTime2,
            'normalSkillTime12' => $normalSkillTime2,
            'fullLVSkillTime12' => $fullLVSkillTime2,
            'skillID13' => $skillID3,
            'skillCoolTime13' => $skillCoolTime3,
            'normalSkillTime13' => $normalSkillTime3,
            'fullLVSkillTime13' => $fullLVSkillTime3,
            'skillID14' => $skillID4,
            'skillCoolTime14' => $skillCoolTime4,
            'normalSkillTime14' => $normalSkillTime4,
            'fullLVSkillTime14' => $fullLVSkillTime4,
            'skillID15' => $skillID5,
            'skillCoolTime15' => $skillCoolTime5,
            'normalSkillTime15' => $normalSkillTime5,
            'fullLVSkillTime15' => $fullLVSkillTime5,
            'createTime2' => $createTime,
        ]);
    }

    public function GetRecoveryData(int $playerID) : mixed{
        return $this->MainAccessor()->FromTable('RecoveryData')->WhereEqual('PlayerID', $playerID)->Fetch();
    }

}