<?php

namespace Games\Skills\Formula;

use Games\Consts\SkillFormula;
use Games\Players\PlayerUtility;
/**
 * Description of OperandEnv
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class OperandEnv extends BaseOperand{
    
    public function Process(): float {
        
        if(empty($this->factory->maxEffect)) return 0;
        
        return match ($this->factory->maxEffect->TypeValue){
            SkillFormula::MaxEffectEnvDune => PlayerUtility::AdaptValueByPoint($this->factory->player->dune),
            SkillFormula::MaxEffectEnvCraterLake => PlayerUtility::AdaptValueByPoint($this->factory->player->craterLake),
            SkillFormula::MaxEffectEnvVolcano => PlayerUtility::AdaptValueByPoint($this->factory->player->volcano),
            default => 0
        };
    }
}
