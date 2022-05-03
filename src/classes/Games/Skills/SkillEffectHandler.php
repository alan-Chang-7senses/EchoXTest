<?php

namespace Games\Skills;

use Games\Players\PlayerHandler;
use Games\Pools\SkillEffectPool;
use Games\Races\RacePlayerHandler;
use Games\Skills\Holders\SkillEffectHolder;
use Games\Skills\SkillEffectFormula;
use Games\Skills\SkillHandler;
use stdClass;
/**
 * Description of SkillEffectHandler
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class SkillEffectHandler {
    
    private SkillEffectPool $pool;
    private int|string $id;
    private SkillEffectHolder|stdClass $info;
    
    public function __construct(int|string $id) {
        $this->pool = SkillEffectPool::Instance();
        $this->id = $id;
        $this->info = $this->pool->$id;
    }
    
    public function GetInfo() : SkillEffectHolder|stdClass{
        return $this->info;
    }
    
    public function GetFormulaResult(SkillHandler $skill, PlayerHandler $player, RacePlayerHandler|null $racePlayer) : float{
        
        $formula = new SkillEffectFormula($skill, $this->info->formula, $player, $racePlayer);
        return $formula->Process();
    }
}
