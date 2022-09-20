<?php

namespace Games\Players\Adaptability;

use Games\Consts\DNAWind;
/**
 * Description of WindAdaptability
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class WindAdaptability extends BaseAdaptability {
    
    public int $tailwind = 0;
    public int $crosswind = 0;
    public int $headwind = 0;

    public function Assign(string $code): void {
        
        if($code == DNAWind::Tailwind) ++$this->tailwind;
        else if($code == DNAWind::Crosswind) ++$this->crosswind;
        else if($code == DNAWind::Headwind) ++$this->headwind;
        // else if($code == $this->headwind) ++$this->headwind;
    }
}
