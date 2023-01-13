<?php

namespace Processors\Store\Google;

use Holders\ResultData;
use Processors\Store\Purchase\BaseCancel;

/*
 * Description of Google Cancel
 * Google 儲值取消購買
 */

class Cancel extends BaseCancel {
    public function Process(): ResultData {
        return $this->Cancel();                
    }

}
