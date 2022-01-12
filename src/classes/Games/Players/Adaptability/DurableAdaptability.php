<?php

namespace Games\Players\Adaptability;

use Games\Consts\DNADurable;
/**
 * Description of DurableAdaptability
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class DurableAdaptability extends BaseAdaptability{
    
    public int $mid = 0;
    public int $long = 0;
    public int $short = 0;

    public function Assign(string $code): void {
        
        if($code == DNADurable::Mid) ++$this->mid;
        else if($code == DNADurable::Long) ++$this->long;
        else if($code == DNADurable::Short) ++$this->short;
    }
}
