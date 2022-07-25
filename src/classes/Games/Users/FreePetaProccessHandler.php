<?php
namespace Games\Users;

use Games\Consts\RaceValue;
use Games\Exceptions\UserException;
use Games\Pools\FreePetaProccessPool;
use Games\Pools\UserPool;
use Games\Users\Holders\FreePetaProccessHolder;
use Games\Users\Holders\UserInfoHolder;
use stdClass;
/**
 * Description of UserHandler
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class FreePetaProccessHandler {
    
    private FreePetaProccessPool $pool;
    private int|string $id;
    private FreePetaProccessHolder |stdClass $info;
    
    public function __construct(int|string $id) {
        $this->pool = FreePetaProccessPool::Instance();
        $this->id = $id;
        $info = $this->pool->$id;
        if($info === false)
        throw new UserException(UserException::UserNameNotSetYet, ['[user]' => $id]);        
        $this->info = $info;
    }
    
    private function ResetInfo() : void{
        $this->info = $this->pool->{$this->id};
    }

    public function GetInfo() : FreePetaProccessHolder|stdClass{
        return $this->info;
    }
    
    public function SaveData(array $bind) : void{
        $this->pool->Save($this->id, 'Data', $bind);
        $this->ResetInfo();
    }
    
    // public function LeaveRace(){
    //     $this->pool->Set($this->id, 'race', RaceValue::NotInRace);
    // }
}
