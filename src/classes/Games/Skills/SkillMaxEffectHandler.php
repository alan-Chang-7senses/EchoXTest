<?php

namespace Games\Skills;

use Games\Consts\SkillValue;
use Games\Players\PlayerHandler;
use Games\Pools\SkillMaxEffectPool;
use Games\Races\RacePlayerHandler;
use Games\Skills\Holders\SkillMaxEffectHolder;
use Games\Skills\SkillEffectFormula;
use Games\Skills\SkillHandler;
use stdClass;
/**
 * Description of SkillMaxEffectHandler
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class SkillMaxEffectHandler {
    
    private SkillMaxEffectPool $pool;
    private int|string $id;
    private SkillMaxEffectHolder|stdClass $info;
    
    public function __construct(int|string $id) {
        $this->pool = SkillMaxEffectPool::Instance();
        $this->id = $id;
        $this->info = $this->pool->$id;
    }
    
    public function GetInfo() : SkillMaxEffectHolder|stdClass{
        return $this->info;
    }
    
    public function GetFormulaResult(SkillHandler $skill, PlayerHandler $player, RacePlayerHandler|null $racePlayer) : float{
        
        $formula = new SkillEffectFormula($skill, $this->info->formula, $player, $racePlayer);
        return $formula->Process();
    }    
    public function GetAllRankFormulaResults(SkillHandler $skill, PlayerHandler $player){
        $rt = [];
        for($i = SkillValue::LevelMin; $i <= SkillValue::LevelMax; $i++)
        {
            $formula = new SkillEffectFormula($skill,$this->info->formula,$player,null,$i);
            $rt[] = $formula->Process();
        }
        return $rt;
    }
}
