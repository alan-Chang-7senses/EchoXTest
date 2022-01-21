<?php

namespace Games\Skills\Formula;

use Games\Consts\SkillFormula;
/**
 * Description of OperandClimate
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class OperandClimate extends BaseOperand{
    
    public function Process(): float {
        
        if(empty($this->factory->maxEffect)) return 0;
        
        return match ($this->factory->maxEffect->TypeValue){
            SkillFormula::MaxEffectClimateSunny => $this->factory->player->sunny,
            SkillFormula::MaxEffectClimateAurora => $this->factory->player->aurora,
            SkillFormula::MaxEffectClimateSandDust => $this->factory->player->sandDust,
            default => 0
        };
    }
}
