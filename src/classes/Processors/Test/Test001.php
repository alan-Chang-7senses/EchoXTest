<?php
namespace Processors\Test;

use Consts\ErrorCode;
use Holders\ResultData;
use Processors\BaseProcessor;
/**
 * Description of Test001
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class Test001 extends BaseProcessor{
    
    protected bool $mustSigned = false;
    
    public function Process(): ResultData {
        
        $result = new ResultData(ErrorCode::Success, 'OK');
        return $result;
    }

}
