<?php

namespace Games\Skills\Formula;

use Games\Consts\SceneValue;
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
            SceneValue::Dune => PlayerUtility::AdaptValueByPoint($this->factory->player->dune),
            SceneValue::CraterLake => PlayerUtility::AdaptValueByPoint($this->factory->player->craterLake),
            SceneValue::Volcano => PlayerUtility::AdaptValueByPoint($this->factory->player->volcano),
            default => 0
        };
    }
}
