<?php

namespace Games\Skills\Formula;

use Games\Consts\SceneValue;
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
            // SceneValue::Sunny => PlayerUtility::AdaptValueByPoint($this->factory->player->sunny),
            // SceneValue::Aurora => PlayerUtility::AdaptValueByPoint($this->factory->player->aurora),
            // SceneValue::SandDust => PlayerUtility::AdaptValueByPoint($this->factory->player->sandDust),
            default => 0
        };
    }
}
