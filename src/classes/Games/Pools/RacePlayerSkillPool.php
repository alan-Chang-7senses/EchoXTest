<?php

namespace Games\Pools;

use Accessors\PoolAccessor;
use stdClass;
use Games\Accessors\RaceAccessor;
/**
 * Description of RacePlayerSkillPool
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class RacePlayerSkillPool extends PoolAccessor{
    
    private static RacePlayerSkillPool $instance;

    public static function Instance() : RacePlayerSkillPool{
        if(empty(self::$instance)) self::$instance = new RacePlayerSkillPool ();
        return self::$instance;
    }
    
    protected string $keyPrefix = 'racePlayerSkill_';

    public function FromDB(int|string $id): stdClass|false {
        
        $raceAccessor = new RaceAccessor();
        $holder = new stdClass();
        $holder->rows = $raceAccessor->rowsPlayerSkillByRacePlayerID($id);
        return $holder;
    }
}
