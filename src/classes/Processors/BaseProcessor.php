<?php

namespace Processors;

use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\Globals;
use Consts\Predefined;
use Consts\Sessions;
use Exception;
use Exceptions\NormalException;
use Games\Accessors\GameLogAccessor;
use Helpers\LogHelper;
use Holders\ResultData;
/**
 * Description of BaseProcessor
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
abstract class BaseProcessor {
    
    abstract function Process() : ResultData;
    
    protected bool $mustSigned = true;
    protected bool $maintainMode = true;
    protected bool $resposeJson = true;

    public function __construct() {
        
        $GLOBALS[Globals::RESULT_RESPOSE_JSON] = $this->resposeJson;
        
        if(getenv(EnvVar::Maintain) === Predefined::Maintaining && $this->maintainMode) throw new NormalException (ErrorCode::Maintain);
        
        if($this->mustSigned && empty($_SESSION[Sessions::Signed]))
            throw new NormalException(NormalException::SignOut);
    }
    
    protected function RecordLog() : void{}
    
    public function __destruct() {
        
        restore_error_handler();
        
        try{
            
            $this->RecordLog();
            (new GameLogAccessor())->AddBaseProcess();
            
        } catch (Exception $ex) {

            LogHelper::Save($ex);
        }
        
    }
}
