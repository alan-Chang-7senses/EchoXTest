<?php

namespace Games\Skills\Formula;

/**
 * 速度
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class OperandSPD extends BaseOperand{
    
    public function Process(): float {
        return $this->factory->player->velocity;
    }
}
