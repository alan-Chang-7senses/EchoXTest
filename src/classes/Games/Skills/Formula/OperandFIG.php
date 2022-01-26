<?php

namespace Games\Skills\Formula;

/**
 * 鬥志
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class OperandFIG extends BaseOperand{
    
    public function Process(): float {
        return $this->factory->player->will;
    }
}
