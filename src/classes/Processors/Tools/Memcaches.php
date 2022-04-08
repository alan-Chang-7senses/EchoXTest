<?php

namespace Processors\Tools;

use Accessors\MemcacheAccessor;
use Consts\ErrorCode;
use Helpers\InputHelper;
use Holders\ResultData;
/**
 * Description of Memcaches
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class Memcaches extends BaseTools{
    
    public function Process(): ResultData {
        
        $method = InputHelper::post('method');
        $key = InputHelper::post('key');
        $value = InputHelper::post('value');
        
        $result = new ResultData(ErrorCode::Success);
        $result->result = MemcacheAccessor::Instance()->$method($key, $value);
        
        return $result;
    }
}
