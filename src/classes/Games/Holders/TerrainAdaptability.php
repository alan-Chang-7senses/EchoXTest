<?php

namespace Games\Holders;

use Games\Consts\DNATerrain;
/**
 * Description of TerrainAdaptability
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class TerrainAdaptability extends BaseAdaptability{
    
    public int $flat = 0;
    public int $upslope = 0;
    public int $downslope = 0;
    
    public function Assign(string $code) {
        
        if($code == DNATerrain::Flat) ++$this->flat;
        else if($code == DNATerrain::Upslope) ++$this->upslope;
        else if($code == DNATerrain::Downslope) ++$this->downslope;
    }
}
