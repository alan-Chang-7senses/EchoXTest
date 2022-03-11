<?php

namespace Games\Skills\Formula;

use Games\Consts\SkillValue;
/**
 * Description of FormulaH
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class OperandH extends BaseOperand{
    
    public function Process(): float {
        return SkillValue::SkillH;
    }
}
