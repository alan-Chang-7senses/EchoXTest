<?php

namespace Games\Skills\Formula;

use Games\Consts\SkillFormula;
/**
 * Description of OperandEnv
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class OperandEnv extends BaseOperand{
    
    public function Process(): float {
        
        if(empty($this->factory->maxEffect)) return 0;
        
        return match ($this->factory->maxEffect->TypeValue){
            SkillFormula::MaxEffectEnvDune => $this->factory->player->dune,
            SkillFormula::MaxEffectEnvCraterLake => $this->factory->player->craterLake,
            SkillFormula::MaxEffectEnvVolcano => $this->factory->player->volcano,
            default => 0
        };
    }
}
