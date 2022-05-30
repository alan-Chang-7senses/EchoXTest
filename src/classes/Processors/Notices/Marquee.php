<?php

namespace Processors\Notices;

use Consts\ErrorCode;
use Games\Pools\MarqueePool;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\BaseProcessor;
/**
 * Description of Marquee
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class Marquee extends BaseProcessor{
    
    protected bool $mustSigned = false;
    
    public function Process(): ResultData {
        
        $lang = InputHelper::post('lang');
        
        $marquee = MarqueePool::Instance()->$lang;
        
        $result = new ResultData(ErrorCode::Success);
        $result->lang = $marquee->lang;
        $result->contents = $marquee->contents;
        
        return $result;
    }
}
