<?php

namespace Games\Skills\Formula;

/**
 * 剩餘耐力
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class OperandHP extends BaseOperand{
    
    public function Process(): float {
        return $this->factory->player->hp;
    }
}
