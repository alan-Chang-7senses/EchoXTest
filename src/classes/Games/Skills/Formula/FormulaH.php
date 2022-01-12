<?php

namespace Games\Skills\Formula;

use Games\Consts\SkillFormula;
/**
 * Description of FormulaH
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class FormulaH extends BaseFormula{
    
    public function Process(): float {
        return SkillFormula::SkillH;
    }
}
