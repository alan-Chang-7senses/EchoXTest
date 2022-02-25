<?php

namespace Processors\Tools;

use Consts\EnvVar;
use Consts\ErrorCode;
use Exception;
use Helpers\InputHelper;
use Processors\BaseProcessor;
/**
 * Description of BaseTools
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
abstract class BaseTools extends BaseProcessor{
    
    protected bool $mustSigned = false;
    
    public function __construct() {
        parent::__construct();
        
        $env = InputHelper::post('env');
        
        if($env != getenv(EnvVar::SysEnv)) throw new Exception ('You do not have this permission', ErrorCode::VerifyError);
    }
}
