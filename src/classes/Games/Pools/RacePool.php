<?php

namespace Games\Pools;

use Accessors\PoolAccessor;
use Games\Accessors\RaceAccessor;
use Games\Races\Holders\RaceInfoHolder;
use Generators\DataGenerator;
use stdClass;
/**
 * Description of RacePool
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class RacePool extends PoolAccessor{
    
    private static RacePool $instance;
    
    public static function Instance() : RacePool {
        if(empty(self::$instance)) self::$instance = new RacePool ();
        return self::$instance;
    }

    protected string $keyPrefix = 'race_';

    public function FromDB(int|string $id): stdClass|false {
        
        $raceAccessor = new RaceAccessor();
        $row = $raceAccessor->rowInfoByID($id);
        
        $holder = new RaceInfoHolder();
        $holder->id = $id;
        $holder->scene = $row->SceneID;
        $holder->windDirection = $row->WindDirection;
        
        $rows = $raceAccessor->rowsPlayerByRaceIDFetchAssoc($id);
        $holder->racePlayers = array_column($rows, 'RacePlayerID');
        
        return DataGenerator::ConventType($holder, 'stdClass');
    }
}
