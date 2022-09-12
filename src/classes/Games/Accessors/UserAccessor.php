<?php

namespace Games\Accessors;

use Games\Consts\PlayerValue;
use Games\Consts\RaceValue;
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
    
    public function rowsByIdleBotAssoc(int $amount) : array{
        return $this->MainAccessor()->FromTable('Users')->
                WhereLess('UserID', PlayerValue::BotIDLimit)->WhereEqual('Race', RaceValue::NotInRace)->
                Limit($amount)->FetchStyleAssoc()->FetchAll();
    }

    public function rowPowerByID(int $id) : mixed{
        return $this->MainAccessor()->FromTableJoinUsing('UserPower','Users','INNER','UserID')
                    ->WhereEqual('UserId',$id)                    
                    ->Fetch();
    }
    
    public function ModifyUserValuesByID(int $id, array $bind) : bool{
        $bind['UpdateTime'] = time();
        return $this->MainAccessor()->FromTable('Users')->WhereEqual('UserID', $id)->Modify($bind);
    }

    public function UpdatePowerTimeByID(int $id, array $bind) : bool{
        $bind['UserID'] = $id;
        return $this->MainAccessor()->FromTable('UserPower')->Add($bind,true);
    }
    
    public function DeleteUserSessionByUserId(int $userID) : bool{
        return $this->MainAccessor()->FromTable('Sessions')->WhereEqual('UserID', $userID)->Delete();
    }
}
