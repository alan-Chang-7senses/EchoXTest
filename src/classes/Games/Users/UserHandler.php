<?php
namespace Games\Users;

use Games\Exceptions\UserException;
use Games\Pools\UserPool;
use Games\Users\Holders\UserInfoHolder;
use Generators\DataGenerator;
/**
 * Description of UserHandler
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class UserHandler {
    
    private UserPool $pool;
    private UserInfoHolder|false $info;
    
    public function __construct(int|string $id) {
        $this->pool = UserPool::Instance();
        $info = $this->pool->$id;
        if($info === false) throw new UserException(UserException::UserNotExist, ['[user]' => $id]);
        $this->info = DataGenerator::ConventType($info, 'Games\Users\Holders\UserInfoHolder');
    }
    
    public function GetInfo() : UserInfoHolder{
        return $this->info;
    }
    
    public function SaveRace(int $raceID) : void{
        $this->pool->Save($this->info->id, 'race', $raceID);
    }
}
