<?php

namespace Games\Skills\Formula;

use Games\Consts\SkillFormula;
/**
 * Description of FormulaS
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class OperandS extends BaseOperand{
    
    public function Process(): float {
        return SkillFormula::SkillS;
    }
}
