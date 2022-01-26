<?php

namespace Games\Skills\Formula;

/**
 * 耐力
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class OperandSTA extends BaseOperand {
    
    public function Process(): float {
        return $this->factory->player->stamina;
    }
}
