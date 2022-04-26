<?php

namespace Games\Races;

use Games\Pools\RacePlayerEffectPool;
use stdClass;
/**
 * Description of RacePlayerEffectHandler
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class RacePlayerEffectHandler {
    
    private int|string $id;
    private RacePlayerEffectPool $pool;
    
    public stdClass $info;
    
    public function __construct(int|string $id) {
        $this->id = $id;
        $this->pool = RacePlayerEffectPool::Instance();
        $this->info = $this->pool->$id;
    }
    
    public function Add(array $bind) : void{
        $this->pool->Save($this->id, 'NewData', $bind);
    }
}
