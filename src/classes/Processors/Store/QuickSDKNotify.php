<?php

namespace Processors\Store;

use Consts\ErrorCode;
use Holders\ResultData;
use Processors\BaseProcessor;

/*
 * Description of QuickSDKNotify
 */

class QuickSDKNotify extends BaseProcessor {

    public function Process(): ResultData {
        return new ResultData(ErrorCode::Success);
    }

}
