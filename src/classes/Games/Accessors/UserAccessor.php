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
}
