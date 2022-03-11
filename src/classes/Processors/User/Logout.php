<?php

namespace Processors\User;

use Consts\ErrorCode;
use Holders\ResultData;
use Processors\BaseProcessor;
/**
 * Description of Logout
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class Logout extends BaseProcessor{
    
    public function Process(): ResultData {
        
        session_destroy();
        
        return new ResultData(ErrorCode::Success);
    }
}
