<?php
namespace Games\Users;

use Games\Exceptions\UserException;
use Games\Pools\UserPool;
use Games\Users\Holders\UserInfoHolder;
use stdClass;
/**
 * Description of UserHandler
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class UserHandler {
    
    private UserPool $pool;
    private int|string $id;
    private UserInfoHolder|stdClass $info;
    
    public function __construct(int|string $id) {
        $this->pool = UserPool::Instance();
        $this->id = $id;
        $info = $this->pool->$id;
        if($info === false) throw new UserException(UserException::UserNotExist, ['[user]' => $id]);
        $this->info = $info;
    }
    
    private function ResetInfo() : void{
        $this->info = $this->pool->{$this->id};
    }

    public function GetInfo() : UserInfoHolder|stdClass{
        return $this->info;
    }
    
    public function SaveData(array $bind) : void{
        $this->pool->Save($this->id, 'Data', $bind);
        $this->ResetInfo();
    }
}
