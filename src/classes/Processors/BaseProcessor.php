<?php

namespace Processors;

use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\Globals;
use Consts\Predefined;
use Consts\ResposeType;
use Consts\Sessions;
use Exceptions\NormalException;
use Games\Accessors\GameLogAccessor;
use Helpers\LogHelper;
use Holders\ResultData;
use Throwable;
/**
 * Description of BaseProcessor
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
abstract class BaseProcessor {
    
    abstract function Process() : ResultData;
    
    protected bool $mustSigned = true;
    protected bool $maintainMode = true;
    protected int $resposeType = ResposeType::JSON;

    public function __construct() {
        
        // $GLOBALS[Globals::RESULT_RESPOSE_TYPE] = $this->resposeType;
        
        // if(getenv(EnvVar::Maintain) === Predefined::Maintaining && $this->maintainMode) throw new NormalException (ErrorCode::Maintain);
        
        // if($this->mustSigned && empty($_SESSION[Sessions::Signed]))
        //     throw new NormalException(NormalException::SignOut);
    }
    
    // protected function RecordLog() : void{}
    
    // public function __destruct() {
        
    //     try{
            
    //         $this->RecordLog();
    //         (new GameLogAccessor())->AddBaseProcess();
            
    //     } catch (Throwable $ex) {

    //         LogHelper::Save($ex);
    //     }
        
    // }
}
