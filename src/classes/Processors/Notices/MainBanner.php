<?php

namespace Processors\Notices;

use Processors\BaseProcessor;
use Consts\ErrorCode;
use Holders\ResultData;
use Helpers\InputHelper;
use Accessors\PDOAccessor;
use Games\Consts\NoticeValue;
use Consts\EnvVar;
/**
 * Description of MainBanner
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class MainBanner extends BaseProcessor{
    
    public function Process(): ResultData {
        
        $lang = InputHelper::post('lang');
        
        $accessor = new PDOAccessor(EnvVar::DBStatic);
        $rows = $accessor->SelectExpr('ImageURL AS image, PageURL AS page')->FromTable('MainBanner')->WhereEqual('Status', NoticeValue::StatusEnabled)->WhereEqual('Lang', $lang)->OrderBy('Serial')->FetchAll();
        
        $result = new ResultData(ErrorCode::Success);
        $result->banner = $rows;
        return $result;
    }
}
