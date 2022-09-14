<?php

namespace Games\PVE;

use Games\Pools\PVELevelPool;
use Games\PVE\Holders\PVELevelInfoHolder;
use stdClass;

class PVELevelHandler
{
    private PVELevelPool $pool;
    private int|string $id;
    private PVELevelInfoHolder|stdClass $info;
    
    public function __construct(int|string $levelID) {
        $this->pool = PVELevelPool::Instance();
        $this->id = $levelID;
        $info = $this->pool->$levelID;
        $this->info = $info;
    }    

    public function GetInfo() : PVELevelInfoHolder|stdClass{
        return $this->info;
    }
    public function GetLevelReward()
    {
        //需要知道玩家是否是初次通關
        
    }
}