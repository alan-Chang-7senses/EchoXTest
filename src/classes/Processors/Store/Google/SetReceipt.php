<?php

namespace Processors\Store\Google;

use Processors\Store\Purchase\BaseSetReceipt;

/*
 * Description of Google Cancel
 * Google 儲值設定收據
 */

class SetReceipt extends BaseSetReceipt {

    public function GetRecepit(): string {
        return InputHelper::post('receipt');        
    }
}