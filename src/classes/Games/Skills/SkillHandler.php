<?php

namespace Games\Skills;

use Games\Pools\SkillPool;
use Games\Skills\Holders\SkillInfoHolder;
use Generators\DataGenerator;
/**
 * Description of SkillHandler
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class SkillHandler {
    
    private SkillPool $pool;
    private int|string $id;
    private SkillInfoHolder $info;
    
    public function __construct(int|string $id) {
        $this->pool = SkillPool::Instance();
        $this->id = $id;
        $this->info = DataGenerator::ConventType($this->pool->$id, 'Games\Skills\Holders\SkillInfoHolder');
    }
    
    public function GetInfo() : SkillInfoHolder{
        return $this->info;
    }
}
