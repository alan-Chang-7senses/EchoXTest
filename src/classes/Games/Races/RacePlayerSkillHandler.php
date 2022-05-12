<?php

namespace Games\Races;

use Games\Pools\RacePlayerSkillPool;
use stdClass;
/**
 * Description of RacePlayerSkillHandler
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class RacePlayerSkillHandler {
    
    private int|string $id;
    private RacePlayerSkillPool $pool;
    
    public stdClass $info;
    
    public function __construct(int|string $id) {
        $this->id = $id;
        $this->pool = RacePlayerSkillPool::Instance();
        $this->info = $this->pool->$id;
    }
    
    public function Add(array $bind) : void{
        $this->pool->Save($this->id, 'NewData', $bind);
    }
}
