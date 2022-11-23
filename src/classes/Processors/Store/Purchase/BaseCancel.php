<?php

namespace Processors\Store\Purchase;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\Sessions;
use Games\Consts\StoreValue;
use Games\Exceptions\StoreException;
use Games\Store\StoreHandler;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\BaseProcessor;

/*
 * Description of PurchaseCancel
 * 儲值取消購買
 */

abstract class BaseCancel extends BaseProcessor {

    protected function Cancel(): ResultData {

        $orderID = InputHelper::post('orderID');

        $userID = $_SESSION[Sessions::UserID];
        $accessor = new PDOAccessor(EnvVar::DBMain);
        $row = $accessor->FromTable('StorePurchaseOrders')->WhereEqual("OrderID", $orderID)->WhereEqual("UserID", $userID)->fetch();
        if (empty($row)) {
            throw new StoreException(StoreException::Error,  ['[cause]' => "no data"]);
        }

        if ($row->Status == StoreValue::PurchaseStatusCancel) {
            throw new StoreException(StoreException::PurchaseCancelled);
        }

        if ($row->Status == StoreValue::PurchaseStatusFinish) {
            throw new StoreException(StoreException::PurchaseIsComplete);
        }

        //取消訂單               
        $storeHandler = new StoreHandler($userID);
        $storeHandler->UpdatePurchaseOrderStatus($orderID, StoreValue::PurchaseStatusCancel);
        return new ResultData(ErrorCode::Success);
    }

}
