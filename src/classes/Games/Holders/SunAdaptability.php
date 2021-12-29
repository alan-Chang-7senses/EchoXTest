<?php

namespace Games\Holders;

use Games\Consts\DNASun;
/**
 * Description of SunAdaptability
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class SunAdaptability extends BaseAdaptability{
    
    public int $normal = 0;
    public int $day = 0;
    public int $night = 0;
    
    public function Assign(string $code) : void {
        
        if($code == DNASun::Normal) ++$this->normal;
        else if($code == DNASun::Day) ++$this->day;
        else if($code == DNASun::Night) ++$this->night;
    }
    
    public function Value() : int {
        
        $value = [DNASun::Normal => $this->normal, DNASun::Day => $this->day, DNASun::Night => $this->night];
        arsort($value);
        reset($value);
        return key($value);
    }
}
