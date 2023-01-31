<?php

namespace Processors\Store\Google;

use Consts\Sessions;
use Games\Consts\StoreValue;
use Games\Store\PurchaseUtility;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\Store\Purchase\BaseRefresh;
use stdClass;

/*
 * Description of Google Refresh
 * (不使用)更新目前購買格狀態，和貨幣資訊
 */

class Refresh extends BaseRefresh {

    protected int $nowPlat = StoreValue::PlatGoogle;

    public function PurchaseVerify(stdClass $purchaseOrders): stdClass {
        return PurchaseUtility::VerifyGoogle($purchaseOrders->ProductID, $purchaseOrders->Receipt);
    }

    public function Process(): ResultData {
        $this->orderID = InputHelper::post('orderID');
        $this->userID = $_SESSION[Sessions::UserID];
        return $this->HandleRefresh();
    }

}
