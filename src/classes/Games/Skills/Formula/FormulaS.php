<?php

namespace Games\Skills\Formula;

use Games\Consts\SkillFormula;
/**
 * Description of FormulaS
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class FormulaS extends BaseFormula{
    
    public function Process(): float {
        return SkillFormula::SkillS;
    }
}
