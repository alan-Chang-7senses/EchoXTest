<?php

namespace Games\Accessors;

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
    
    public function AddUserLogin(int $userID) : bool{
        return $this->EliteTestAccessor()->FromTable('UserLogin')->Add([
            'UserID' => $userID,
            'RecordTime' => time()
        ]);
    }
    
    public function IncreaseTotalLoginHours(int $hour) : bool{
        return $this->EliteTestAccessor()->executeBind('UPDATE `TotalLoginHours` SET `Amount` = `Amount` + 1, `UpdateTime` = :updateTime WHERE Hours = :hour', [
            'updateTime' => time(),
            'hour' => $hour,
        ]);
    }
}
