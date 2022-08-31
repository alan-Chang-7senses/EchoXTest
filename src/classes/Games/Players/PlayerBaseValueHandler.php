<?php

namespace Games\Players;

use Games\Exceptions\PlayerException;
use Games\Players\Holders\PlayerBaseValueInfoHolder;
use Games\Players\Holders\PlayerInfoHolder;
use Games\Pools\PlayerBaseValuePool;
use stdClass;

class PlayerBaseValueHandler {
    
    private PlayerBaseValuePool $pool;
    private PlayerBaseValueInfoHolder|stdClass $info;

    public function __construct(int|string $id) {
        $this->pool = PlayerBaseValuePool::Instance();
        $info = $this->pool->$id;
        if($info === false) throw new PlayerException(PlayerException::PlayerNotExist, ['[player]' => $id]);
        $this->info = $info;
    }
    
    public function GetInfo() : PlayerBaseValueInfoHolder|stdClass{
        return $this->info;
    }            
}
