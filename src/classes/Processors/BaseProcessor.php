<?php

namespace Processors;

use Consts\Session;
use Exceptions\LoginException;
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
        
        if($this->mustSigned && empty($_SESSION[Session::Signed])) throw new LoginException(LoginException::SignOut);
    }
}
