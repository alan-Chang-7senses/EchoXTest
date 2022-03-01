<?php

namespace Games\Skills;

use Games\Players\PlayerHandler;
use Games\Pools\SkillEffectPool;
use Games\Skills\Holders\SkillEffectHolder;
use Generators\DataGenerator;
/**
 * Description of SkillEffectHandler
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class SkillEffectHandler {
    
    private SkillEffectPool $pool;
    private int|string $id;
    private SkillEffectHolder $info;
    private PlayerHandler $playerHandler;
    
    public function __construct(int|string $id) {
        $this->pool = SkillEffectPool::Instance();
        $this->id = $id;
        $this->info = DataGenerator::ConventType($this->pool->$id, 'Games\Skills\Holders\SkillEffectHolder');
    }
    
    public function GetInfo() : SkillEffectHolder{
        return $this->info;
    }
    
    public function SetPlayer(PlayerHandler $handler) : void{
        $this->playerHandler = $handler;
    }
}
