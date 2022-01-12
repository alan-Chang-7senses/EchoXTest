<?php

namespace Games\Skills\Formula;

/**
 * Description of BaseFormula
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
abstract class BaseFormula {
    
    protected int $levelN;
    
    public function __construct(int $levelN) {
        $this->levelN = $levelN;
    }
    
    abstract function Process() : float;
}
