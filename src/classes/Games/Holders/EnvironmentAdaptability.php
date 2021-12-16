<?php

namespace Games\Holders;

use Games\Consts\EnvType;
/**
 * Description of EnvironmentAdaptability
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class EnvironmentAdaptability extends BaseAdaptability {
    
    public int $dune = 0;
    public int $volcano = 0;
    public int $craterLake = 0;
    
    public function Assign(string $code){
        
        if($code == EnvType::Dune) ++$this->dune;
        else if($code == EnvType::Volcano) ++$this->volcano;
        else if($code == EnvType::CraterLake) ++$this->craterLake;
    }
}
