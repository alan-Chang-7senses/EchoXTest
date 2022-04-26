<?php

namespace Games\Pools;

use Accessors\PoolAccessor;
use Games\Accessors\RaceAccessor;
use stdClass;
/**
 * Description of RacePlayerEffectPool
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class RacePlayerEffectPool extends PoolAccessor{
    
    private static RacePlayerEffectPool $instance;
    
    public static function Instance() : RacePlayerEffectPool{
        if(empty(self::$instance)) self::$instance = new RacePlayerEffectPool ();
        return self::$instance;
    }
    
    protected string $keyPrefix = 'racePlayerEffect_';

    public function FromDB(int|string $id): stdClass|false {
        
        $raceAccessor = new RaceAccessor();
        $holder = new stdClass();
        $holder->list = $raceAccessor->rowsPlayerEffectByRacePlayerID($id);
        
        return $holder;
    }
    
    protected function SaveNewData(stdClass $data, array $bind) : stdClass{
        
        $res = (new RaceAccessor())->AddRacePlayerEffect($bind);
        if($res !== false) $data->list[] = (object)$bind;
        
        return $data;
    }
}
