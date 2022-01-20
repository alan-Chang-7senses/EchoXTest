<?php

namespace Games\Skills\Formula;

/**
 * 聰慧
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class OperandINT extends BaseOperand{
    
    public function Process(): float {
        return $this->factory->player->intelligent;
    }

}
