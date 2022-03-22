<?php

namespace Games\Accessors;

use Games\EliteTest\EliteTestValues;
use Generators\ConfigGenerator;
use Generators\DataGenerator;
/**
 * Description of EliteTestAccessor
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class EliteTestAccessor extends BaseAccessor{
    
    public function rowUserByUsername(string $username) : mixed{
        return $this->EliteTestAccessor()->FromTable('Users')->WhereEqual('Username', $username)->Fetch();
    }
    
    public function rowUserByUserID(int $id) : mixed{
        return $this->EliteTestAccessor()->FromTable('Users')->WhereEqual('UserID', $id)->Fetch();
    }
    
    public function rowsUserByUserIDs(array $ids) : array{
        return $this->EliteTestAccessor()->FromTable('Users')->WhereIn('UserID', $ids)->FetchAll();
    }

    public function idsRaceByExpired(float $expried) : array{
        $rows = $this->EliteTestAccessor()->FromTable('Races')
                ->WhereEqual('Status', EliteTestValues::RaceBegin)->WhereLess('CreateTime', $expried)
                ->FetchStyleAssoc()->FetchAll();
        return array_column($rows, 'RaceID');
    }

    public function AddUsers(array $users) : bool{
        return $this->EliteTestAccessor()->FromTable('Users')->AddAll($users);
    }

    public function AddRace() : int{
        
        $accessor = $this->EliteTestAccessor();
        $accessor->FromTable('Races')->Add(['CreateTime' => microtime(true)]);
        return $accessor->LastInsertID();
    }
    
    public function ModifyUserByUserIDs(array $ids, array $bind) : bool{
        $bind['UpdateTime'] = microtime(true);
        return $this->EliteTestAccessor()->FromTable('Users')->WhereIn('UserID', $ids)->Modify($bind);
    }
    
    public function ModifyUserByRaces(array $races, array $bind) : bool{
        $bind['UpdateTime'] = microtime(true);
        return $this->EliteTestAccessor()->FromTable('Users')->WhereIn('Race', $races)->Modify($bind);
    }
    
    public function ModifyRaceByRaceIDs(array $ids, array $bind) : bool{
        return $this->EliteTestAccessor()->FromTable('Races')->WhereIn('RaceID', $ids)->Modify($bind);
    }

    public function AddUserLogin(int $userID) : bool{
        return $this->EliteTestAccessor()->FromTable('UserLogin')->Add([
            'UserID' => $userID,
            'UserIP' => DataGenerator::UserIP(),
            'RecordTime' => time()
        ]);
    }
    
    public function AddRacePlayers(array $rows) : bool{
        return $this->EliteTestAccessor()->FromTable('RacePlayer')->AddAll($rows);
    }
    
    public function AddRaceSkills(array $rows) : bool{
        return $this->EliteTestAccessor()->FromTable('RaceSkills')->AddAll($rows);
    }

    public function IncreaseTotalLoginHours() : bool{
        return $this->EliteTestAccessor()->executeBind('UPDATE `TotalLoginHours` SET `Amount` = `Amount` + 1, `UpdateTime` = :updateTime WHERE Hours = :hour', [
            'updateTime' => time(),
            'hour' => DataGenerator::TodayHourByTimezone(ConfigGenerator::Instance()->TimezoneDefault),
        ]);
    }
    
    public function IncreaseTotalRaceBeginHours() : bool{
        return $this->EliteTestAccessor()->executeBind('UPDATE `TotalRaceBeginHours` SET `Amount` = `Amount` + 1, `UpdateTime` = :updateTime WHERE Hours = :hour', [
            'updateTime' => time(),
            'hour' => DataGenerator::TodayHourByTimezone(ConfigGenerator::Instance()->TimezoneDefault),
        ]);
    }
    
    public function IncreaseTotalUserRaceBeginByUserIDs(array $ids) : array{
        $accessor = $this->EliteTestAccessor()->PrepareName('IncreaseTotalUserRaceBegin');
        $current = time();
        $result = ['success' => 0, 'fail' => 0];
        foreach($ids as $id){
            $res = $accessor->executeBind('INSERT INTO `TotalUserRace` VALUES (:id, 1, 0, :updateTime1) ON DUPLICATE KEY UPDATE `BeginAmount` = `BeginAmount` + 1, `UpdateTime` = :updateTime2', [
                'id' => $id,
                'updateTime1' => $current,
                'updateTime2' => $current,
            ]);
            if($res) ++$result['success'];
            else ++$result['fail'];
        }
        return $result;
    }
    
    public function IncreaseTotalUserRaceFinishByUserIDs(array $ids) : bool{
        $accessor = $this->EliteTestAccessor();
        $values = $accessor->valuesForWhereIn($ids);
        $values->bind['updateTime'] = time();
        return $accessor->executeBind('UPDATE `TotalUserRace` SET `FinishAmount` = `FinishAmount` + 1, `UpdateTime` = :updateTime WHERE UserID IN '.$values->values, $values->bind);
    }
    
    public function IncreaseTotalSkills(array $skillAmounts) : array{
        $accessor = $this->EliteTestAccessor()->PrepareName('IncreaseTotalSkill');
        $current = time();
        $return = [];
        foreach ($skillAmounts as $id => $amount) {
            $res = $accessor->executeBind('INSERT INTO `TotalSkills` VALUES (:id, :amount1, :updateTime1) ON DUPLICATE KEY UPDATE `Amount` = `Amount` + :amount2, `UpdateTime` = :updateTime2', [
                'id' => $id,
                'amount1' => $amount,
                'amount2' => $amount,
                'updateTime1' => $current,
                'updateTime2' => $current,
            ]);
            $return[$id] = $res === false ? -$amount : $amount;
        }
        return $return;
    }

    public function FinishRaceByRaceID(int $id, int $status) : bool{
        $current = microtime(true);
        return $this->EliteTestAccessor()->executeBind('UPDATE `Races` SET `Status` = :status, `FinishTime` = :finishTime1, `Duration` = :finishTime2 - `CreateTime` WHERE `RaceID` = :id', [
            'status' => $status,
            'finishTime1' => $current,
            'finishTime2' => $current,
            'id' => $id,
        ]);
    }
    
    public function FinishUserByUserRaceScore(int $race, array $userScores) : array{
        $accessor = $this->EliteTestAccessor()->PrepareName('FinishUserRace');
        $current = time();
        $return = [];
        foreach ($userScores as $id => $score) {
            $return[$id] = $accessor->executeBind('UPDATE `Users` SET `Race` = :race, `Score` = `Score` + :score, `UpdateTime` = :updateTime WHERE `UserID` = :id', [
                'race' => $race,
                'score' => $score,
                'updateTime' => $current,
                'id' => $id,
            ]);
        }
        return $return;
    }
}
