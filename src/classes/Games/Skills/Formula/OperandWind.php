<?php

namespace Games\Skills\Formula;

use Games\Consts\SceneValue;
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
            SceneValue::Tailwind => PlayerUtility::AdaptValueByPoint($this->factory->player->tailwind),
            SceneValue::Crosswind => PlayerUtility::AdaptValueByPoint($this->factory->player->crosswind),
            SceneValue::Headwind => PlayerUtility::AdaptValueByPoint($this->factory->player->headwind),
            default => 0
        };
    }
}
