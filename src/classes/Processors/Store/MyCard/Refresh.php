<?php

namespace Processors\Store\MyCard;

use Consts\Sessions;
use Games\Consts\StoreValue;
use Games\Store\MyCardUtility;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\Store\Purchase\BaseRefresh;
use stdClass;

/*
 * Description of MyCard Refresh
 * 更新目前購買格狀態，和貨幣資訊，MyCard請款
 */

class Refresh extends BaseRefresh {

    protected int $nowPlat = StoreValue::PlatMyCard;

    public function PurchaseVerify(stdClass $purchaseOrders): stdClass {
        return MyCardUtility::Verify($purchaseOrders->UserID, $purchaseOrders->AuthCode);
    }

    public function Process(): ResultData {
        $this->orderID = InputHelper::post('orderID');
        $this->userID = $_SESSION[Sessions::UserID];
        return $this->HandleRefresh();
    }

}
