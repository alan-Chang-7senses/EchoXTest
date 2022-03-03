<?php

namespace Games\Skills;

use Games\Players\PlayerHandler;
use Games\Pools\SkillMaxEffectPool;
use Games\Skills\Holders\SkillMaxEffectHolder;
use Generators\DataGenerator;
/**
 * Description of SkillMaxEffectHandler
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class SkillMaxEffectHandler {
    
    private SkillMaxEffectPool $pool;
    private int|string $id;
    private SkillMaxEffectHolder $info;
    private PlayerHandler $playerHandler;
    
    public function __construct(int|string $id) {
        $this->pool = SkillMaxEffectPool::Instance();
        $this->id = $id;
        $this->info = DataGenerator::ConventType($this->pool->$id, 'Games\Skills\Holders\SkillMaxEffectHolder');
    }
    
    public function GetInfo() : SkillMaxEffectHolder{
        return $this->info;
    }
    
    public function SetPlayer(PlayerHandler $hanlder) : void{
        $this->playerHandler = $hanlder;
    }
    
    public function GetFormulaResult() : float{
        return 0; //未計算
    }
}
