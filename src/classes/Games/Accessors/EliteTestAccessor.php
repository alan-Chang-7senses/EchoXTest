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
        $return = [];
        foreach($ids as $id){
            $return[$id] = $accessor->executeBind('INSERT INTO `TotalUserRace` VALUES (:id, 1, 0, 0, :updateTime1) ON DUPLICATE KEY UPDATE `BeginAmount` = `BeginAmount` + 1, `UpdateTime` = :updateTime2', [
                'id' => $id,
                'updateTime1' => $current,
                'updateTime2' => $current,
            ]);
        }
        return $return;
    }
    
    public function IncreaseTotalUserRaceFinishByUserIDs(array $ids) : bool{
        $accessor = $this->EliteTestAccessor();
        $values = $accessor->valuesForWhereIn($ids);
        $values->bind['updateTime'] = time();
        return $accessor->executeBind('UPDATE `TotalUserRace` SET `FinishAmount` = `FinishAmount` + 1, `UpdateTime` = :updateTime WHERE UserID IN '.$values->values, $values->bind);
    }
    
    public function IncreaseTotalUserRaceWinByUserIDs(array $ids) : bool{
        $accessor = $this->EliteTestAccessor();
        $values = $accessor->valuesForWhereIn($ids);
        $values->bind['updateTime'] = time();
        return $accessor->executeBind('UPDATE `TotalUserRace` SET `Win` = `Win` + 1, `UpdateTime` = :updateTime WHERE UserID IN '.$values->values, $values->bind);
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
    
    public function rowsUserRanking() : array{
        return $this->EliteTestAccessor()->SelectExpr('@a:=@a+1 Ranking, `UserID`, `Username`, `Score`')->FromTable('`Users` , (SELECT @a:= 0) AS a')
                ->WhereGreater('UserID', 0)->OrderBy('Score', 'DESC')->OrderBy('UserID')->FetchAll();
    }
    
    public function rowsSkillTotal() : array{
        return $this->EliteTestAccessor()->SelectExpr('`SkillID`, COUNT(*) AS cnt')->FromTable('RaceSkills')->WhereGreater('UserID', 0)->GroupBy('SkillID')->OrderBy('SkillID')->FetchAll();
    }
    
    public function rowsTotalRaceBeginHours() : array{
        return $this->EliteTestAccessor()->SelectExpr('`Hours`, `Amount`')->FromTable('TotalRaceBeginHours')->OrderBy('Hours')->FetchAll();
    }
    
    public function rowsFastestFinishTime(bool $fastest) : array{
        return $this->EliteTestAccessor()->executeBindFetchAll('SELECT `UserID`, `Username`, `Score`, FORMAT(`Duration`, 2) AS duration 
FROM `Users` LEFT JOIN `RacePlayer` USING(UserID) 
WHERE `UserID` > 0 AND `Duration` = (
    SELECT `Duration` 
    FROM `RacePlayer` 
    WHERE `UserID` > 0 
    ORDER BY `Duration` '.($fastest ? 'ASC' : 'DESC').' 
    LIMIT 1
)GROUP BY `UserID` ORDER BY UserID', []);
    }
    
    public function rowsTotalLoginHours() : array {
        return $this->EliteTestAccessor()->SelectExpr('`Hours`, `Amount`')->FromTable('TotalLoginHours')->OrderBy('Hours')->FetchAll();
    }
    
    public function rowsMostUserRace(string $column) : array{
        return $this->EliteTestAccessor()->executeBindFetchAll('SELECT `UserID`, `Username`, `BeginAmount`, `FinishAmount`,`Win`,`Score` 
FROM `Users` LEFT JOIN `TotalUserRace` USING(`UserID`) 
WHERE `UserID` > 0 AND `'.$column.'` = (
    SELECT `'.$column.'` 
    FROM `TotalUserRace` 
    WHERE `UserID` > 0 
    ORDER BY `'.$column.'` DESC 
    LIMIT 1
)', []);
    }
    
    public function rowAvgUserRace() : mixed{
        return $this->EliteTestAccessor()->SelectExpr('COUNT(*) AS count, SUM(BeginAmount) AS total, AVG(BeginAmount) AS avg')->FromTable('TotalUserRace')->WhereGreater('UserID', 0)->FetchStyleAssoc()->Fetch();
    }
}
