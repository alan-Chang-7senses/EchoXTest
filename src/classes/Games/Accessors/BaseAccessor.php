<?php

namespace Games\Accessors;

use Accessors\PDOAccessor;
/**
 * Description of BaseAccessor
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
abstract class BaseAccessor {
    
    private PDOAccessor|null $mainAccessor = null;
    private PDOAccessor|null $staticAccessor = null;
    private PDOAccessor|null $logAccessor = null;

    protected function MainAccessor() : PDOAccessor {
        if($this->mainAccessor === null) $this->mainAccessor = new PDOAccessor ('KoaMain');
        else $this->mainAccessor->ClearAll();
        return $this->mainAccessor;
    }
    
    protected function StaticAccessor() : PDOAccessor{
        if($this->staticAccessor === null) $this->staticAccessor = new PDOAccessor ('KoaStatic');
        else $this->staticAccessor->ClearAll();
        return $this->staticAccessor;
    }
    
    protected function LogAccessor() : PDOAccessor{
        if($this->logAccessor == null) $this->logAccessor = new PDOAccessor ('KoaLog');
        else $this->logAccessor->ClearAll ();
        return $this->logAccessor;
    }
}
