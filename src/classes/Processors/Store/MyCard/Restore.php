<?php

namespace Processors\Store\MyCard;

use Games\Consts\StoreValue;
use Games\Store\MyCardUtility;
use Processors\Store\Purchase\BaseRestore;
use stdClass;


/*
 * Description of MyCardRestore
 * Mycard 補儲
 */

class Restore extends BaseRestore {

    protected int $nowPlat = StoreValue::PlatMyCard;
    public function PurchaseVerify(stdClass $purchaseOrders): int {
        return MyCardUtility::MyCardVerify($purchaseOrders->UserID, $purchaseOrders->Receipt);                
    }

}
