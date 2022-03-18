<?php

namespace Games\Accessors;

use Accessors\PDOAccessor;
use Consts\EnvVar;
/**
 * Description of BaseAccessor
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
abstract class BaseAccessor {
    
    protected function MainAccessor() : PDOAccessor {
        return $this->GetAccessor(EnvVar::DBLabelMain);
    }
    
    protected function StaticAccessor() : PDOAccessor{
        return $this->GetAccessor(EnvVar::DBLabelStatic);
    }
    
    protected function LogAccessor() : PDOAccessor{
        return $this->GetAccessor(EnvVar::DBLabelLog);
    }
    
    protected function EliteTestAccessor() : PDOAccessor{
        return $this->GetAccessor(EnvVar::DBLabelEliteTest);
    }

    private function GetAccessor(string $label) : PDOAccessor{
        if(empty($this->$label)) $this->$label = new PDOAccessor (getenv($label));
        else $this->$label->ClearAll ();
        return $this->$label;
    }
}
