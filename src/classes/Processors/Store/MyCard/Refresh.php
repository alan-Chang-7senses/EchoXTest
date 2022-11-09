<?php

namespace Processors\Store\MyCard;

use Games\Consts\StoreValue;
use Games\Store\MyCardUtility;
use Processors\Store\Purchase\BaseRefresh;
use stdClass;

/*
 * Description of PurchaseRefresh
 * 更新目前購買格狀態，和貨幣資訊
 */

class Refresh extends BaseRefresh {

    protected int $nowPlat = StoreValue::PlatMyCard;

    public function PurchaseVerify(stdClass $purchaseOrders): int {

        return MyCardUtility::MyCardVerify($purchaseOrders->UserID, $purchaseOrders->Receipt);
    }

}
