<?php

namespace Processors\Store\MyCard;

use Holders\ResultData;
use Processors\Store\Purchase\BaseCancel;

/*
 * Description of Cancel
 * MyCard儲值取消購買
 */

class Cancel extends BaseCancel {
    public function Process(): ResultData {
        return $this->Cancel();                
    }

}
