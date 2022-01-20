<?php

namespace Games\Skills\Formula;

/**
 * Description of FormulaSPD
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class OperandSPD extends BaseOperand{
    
    public function Process(): float {
        return $this->factory->player->velocity;
    }
}
