<?php

namespace Games\Skills\Formula;

use Games\Consts\SkillFormula;
use Games\Players\PlayerUtility;
/**
 * Description of OperandClimate
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class OperandClimate extends BaseOperand{
    
    public function Process(): float {
        
        if(empty($this->factory->maxEffect)) return 0;
        
        return match ($this->factory->maxEffect->TypeValue){
            SkillFormula::MaxEffectClimateSunny => PlayerUtility::AdaptValueByPoint($this->factory->player->sunny),
            SkillFormula::MaxEffectClimateAurora => PlayerUtility::AdaptValueByPoint($this->factory->player->aurora),
            SkillFormula::MaxEffectClimateSandDust => PlayerUtility::AdaptValueByPoint($this->factory->player->sandDust),
            default => 0
        };
    }
}
