<?php

namespace Games\Skills\Formula;

use Games\Consts\SkillValue;
/**
 * Description of FormulaS
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class OperandS extends BaseOperand{
    
    public function Process(): float {
        return SkillValue::SkillS;
    }
}
