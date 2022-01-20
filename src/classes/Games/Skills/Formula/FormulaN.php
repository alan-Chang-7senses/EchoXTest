<?php

namespace Games\Skills\Formula;

/**
 * Description of FormulaN
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class FormulaN extends BaseFormula {
    
    public function Process(): float {
    
        return $this->factory->skill->ranks[$this->factory->skill->level];
    }
}
