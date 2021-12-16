<?php

namespace Games\Holders;

use Games\Consts\DNAEnv;
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
        
        if($code == DNAEnv::Dune) ++$this->dune;
        else if($code == DNAEnv::Volcano) ++$this->volcano;
        else if($code == DNAEnv::CraterLake) ++$this->craterLake;
    }
}
