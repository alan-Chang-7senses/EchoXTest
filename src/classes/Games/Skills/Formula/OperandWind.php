<?php

namespace Games\Skills\Formula;

use Games\Consts\SkillFormula;
use Games\Players\PlayerUtility;
/**
 * Description of OperandWind
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class OperandWind extends BaseOperand{
    
    public function Process(): float {
    
        if(empty($this->factory->maxEffect)) return 0;
        
        return match ($this->factory->maxEffect->TypeValue){
            SkillFormula::MaxEffectTailwind => PlayerUtility::AdaptValueByPoint($this->factory->player->tailwind),
            SkillFormula::MaxEffectHeadwind => PlayerUtility::AdaptValueByPoint($this->factory->player->headwind),
            SkillFormula::MaxEffectCrosswind => PlayerUtility::AdaptValueByPoint($this->factory->player->crosswind),
            default => 0
        };
    }
}
