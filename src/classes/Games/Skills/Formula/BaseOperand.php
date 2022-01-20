<?php

namespace Games\Skills\Formula;

/**
 * Description of BaseFormula
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
abstract class BaseOperand {
    
    protected FormulaFactory $factory;

    public function __construct(FormulaFactory $factory) {
        $this->factory = $factory;
    }
    
    abstract function Process() : float;
}
