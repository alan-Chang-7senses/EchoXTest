<?php

namespace Processors;

use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\Predefined;
use Consts\Sessions;
use Exceptions\NormalException;
use Games\Accessors\GameLogAccessor;
use Holders\ResultData;
/**
 * Description of BaseProcessor
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
abstract class BaseProcessor {
    
    abstract function Process() : ResultData;
    
    protected bool $mustSigned = true;
    
    public function __construct() {
        
        if(getenv(EnvVar::Maintain) === Predefined::Maintaining) throw new NormalException (ErrorCode::Maintain);
        
        if($this->mustSigned && empty($_SESSION[Sessions::Signed]))
            throw new NormalException(NormalException::SignOut);
    }
    
    protected function RecordLog() : void{}
    
    public function __destruct() {
        $this->RecordLog();
        (new GameLogAccessor())->AddBaseProcess();
    }
}
