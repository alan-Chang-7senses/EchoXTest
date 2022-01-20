<?php

namespace Games\Skills\Formula;

use Games\Consts\SkillMaxEffectEnv;
/**
 * Description of OperandEnv
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class OperandEnv extends BaseOperand{
    
    public function Process(): float {
        
        if(empty($this->factory->maxEffect)) return 0;
        
        return match ($this->factory->maxEffect->TypeValue){
            SkillMaxEffectEnv::Dune => $this->factory->player->dune,
            SkillMaxEffectEnv::CraterLake => $this->factory->player->craterLake,
            SkillMaxEffectEnv::Volcano => $this->factory->player->volcano,
            default => 0
        };
    }
}
