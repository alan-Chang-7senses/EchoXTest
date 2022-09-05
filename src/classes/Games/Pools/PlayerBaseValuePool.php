<?php

namespace Games\Pools;

use Accessors\PDOAccessor;
use Accessors\PoolAccessor;
use Consts\EnvVar;
use Games\Players\Holders\PlayerBaseValueInfoHolder;
use stdClass;

class PlayerBaseValuePool extends PoolAccessor {
    
    private static PlayerBaseValuePool $instance;
    
    public static function Instance() : PlayerBaseValuePool{
        if(empty(self::$instance)) self::$instance = new PlayerBaseValuePool();
        return self::$instance;
    }
    
    protected string $keyPrefix = 'playerBaseValue_';

    public function FromDB(int|string $playerID) : stdClass|false{
        $holder = new PlayerBaseValueInfoHolder();
        $row = (new PDOAccessor(EnvVar::DBMain))->FromTable('PlayerNFT')
                                         ->WhereEqual('PlayerID',$playerID)
                                         ->Fetch();
        if($row === false)return false;
        $holder->constitution = $row->Constitution;
        $holder->strength = $row->Strength;
        $holder->dexterity = $row->Dexterity;
        $holder->agility = $row->Agility;
        return $holder;
    }    
}
