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
            'RecordTime' => time()
        ]);
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
    
    public function IncreaseTotalUserRaceBeginByUserIDs(array $ids) : bool{
        $accessor = $this->EliteTestAccessor();
        $values = $accessor->valuesForWhereIn($ids);
        $values->bind['updateTime'] = time();
        return $accessor->executeBind('UPDATE `TotalUserRace` SET `BeginAmount` = `BeginAmount` + 1, `UpdateTime` = :updateTime WHERE UserID IN '.$values->values, $values->bind);
    }
}
