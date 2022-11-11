<?php

namespace Processors\Interfaces\MyCard;

use Games\Consts\StoreValue;
use Games\Store\MyCardUtility;
use Processors\Store\Purchase\BaseRestore;
use stdClass;

/*
 * Description of MyCardRestore
 * Mycard 補儲
 */

class TransactionComparison extends BaseRestore {

    protected int $nowPlat = StoreValue::PlatMyCard;
    public function PurchaseVerify(stdClass $purchaseOrders): int {
        
             
        return StoreValue::PurchaseProcessRetry;
    }

}