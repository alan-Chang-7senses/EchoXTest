<?php

namespace Games\Accessors;

/**
 * Description of UserAccessor
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class UserAccessor extends BaseAccessor {
    
    public function rowUserByUsername(string $username) : mixed {
        return $this->MainAccessor()->FromTable('Users')->WhereEqual('Username', $username)->Fetch();
    }
    
    public function rowUserByID(int $id) : mixed{
        return $this->MainAccessor()->FromTable('Users')->WhereEqual('UserID', $id)->Fetch();
    }
    
    public function rowSessionByID(string $id) : mixed{
        return $this->MainAccessor()->FromTable('Sessions')->WhereEqual('SessionID', $id)->Fetch();
    }

    public function ModifyRaceByID(int $id, int $race) : bool{
        return $this->MainAccessor()->FromTable('Users')->WhereEqual('UserID', $id)->Modify(['Race' => $race, 'UpdateTime' => time()]);
    }
    
    public function DeleteUserSessionByEarlierTime(int $userID, int $time) : bool{
        return $this->MainAccessor()->FromTable('Sessions')->WhereEqual('UserID', $userID)->WhereLess('SessionExpires', $time)->Delete();
    }
}
