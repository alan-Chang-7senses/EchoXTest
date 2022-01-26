<?php

namespace Games\Players\Adaptability;

/**
 * Description of SlotNumber
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class SlotNumber extends BaseAdaptability{
    
    private array $attrNumber = [];

    public function Assign(string $code): void {
        if(!isset($this->attrNumber[$code])) $this->attrNumber[$code] = 0;
        ++$this->attrNumber[$code];
    }
    
    public function Value() : int{
        
        $cnt = count($this->attrNumber);
        
        if($cnt == 1) return 6;
        else if($cnt == 2) return 5;
        else if($cnt >= 3) return 4;
        else return 0;
    }
}
