<?php

namespace Games\Races;

use Games\Pools\RacePlayerPool;
use Games\Races\Holders\RacePlayerHolder;
use Generators\DataGenerator;
/**
 * Description of RacePlayerHandler
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class RacePlayerHandler {
    
    private RacePlayerPool $pool;
    private RacePlayerHolder $info;
    
    public function __construct(int|string $id) {
        $this->pool = RacePlayerPool::Instance();
        $this->info = DataGenerator::ConventType($this->pool->$id, 'Games\Races\Holders\RacePlayerHolder');
    }
    
    public function GetInfo() : RacePlayerHolder{
        return $this->info;
    }
}
