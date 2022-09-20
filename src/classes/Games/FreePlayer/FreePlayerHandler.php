<?php

namespace Games\FreePlayer;

use Games\Players\Holders\PlayerInfoHolder;
use Games\Players\PlayerHandler;
use Games\Pools\FreePlayerPool;
use stdClass;

class FreePlayerHandler extends PlayerHandler
{
    private FreePlayerPool $pool;
    private PlayerInfoHolder|stdClass $info;
    
    
     /**
     * @param int|string $number 可指定免費角色類型 1~3。
     */
    public function __construct(int|string $number)
    {
        $this->pool = FreePlayerPool::Instance();
        $info = $this->pool->$number;
        $this->info = $info;
    }
    public function GetInfo(): PlayerInfoHolder|stdClass
    {
        return $this->info;
    }
}
