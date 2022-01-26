<?php

namespace Games\Skills\Formula;

/**
 * 爆發力
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class OperandPOW extends BaseOperand{
    
    public function Process(): float {
        return $this->factory->player->breakOut;
    }
}
