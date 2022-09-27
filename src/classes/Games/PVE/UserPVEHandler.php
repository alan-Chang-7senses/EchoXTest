<?php

namespace Games\PVE;

use Games\Pools\UserPVEPool;
use Games\PVE\Holders\UserPVEInfoHolder;
use stdClass;

class UserPVEHandler
{
    private UserPVEPool $pool;
    private int|string $id;
    private UserPVEInfoHolder|stdClass $info;
    
    public function __construct(int|string $userID) {
        $this->pool = UserPVEPool::Instance();
        $this->id = $userID;
        $info = $this->pool->$userID;
        $this->info = $info;
    }    

    public function GetInfo() : UserPVEInfoHolder|stdClass{
        return $this->info;
    }

    //紀錄成績
    // public function ClearLevelAndGetReward(int $userID,int $medalAmount) : array
    // {

    // }


}