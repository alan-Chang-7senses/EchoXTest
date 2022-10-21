<?php

namespace Processors\Store;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\Sessions;
use Games\Consts\StoreValue;
use Games\Exceptions\StoreException;
use Games\Pools\Store\StoreTradesPool;
use Games\Store\StoreUtility;
use Games\Users\UserBagHandler;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\BaseProcessor;

/*
 * Description of PurchaseRefresh
 * 更新目前購買格狀態，和貨幣資訊
 */

class PurchaseRefresh extends BaseProcessor {

    public function Process(): ResultData {

        $orderID = InputHelper::post('orderID');

        $userID = $_SESSION[Sessions::UserID];
        $accessor = new PDOAccessor(EnvVar::DBMain);
        $row = $accessor->FromTable('StorePurchaseOrders')->WhereEqual("OrderID", $orderID)->WhereEqual("UserID", $userID)->fetch();
        if (empty($row)) {
            throw new StoreException(StoreException::Error, ['[des]' => "no data"]);
        }

        if ($row->Status == StoreValue::PurchaseQuickSDKFailure) {
            throw new StoreException(StoreException::PurchaseFailure);
        }

        if ($row->Status == StoreValue::PurchaseStatusCancel) {
            throw new StoreException(StoreException::PurchaseCancelled);
        }

        if ($row->Status == StoreValue::PurchaseStatusProcessing) {
            throw new StoreException(StoreException::PurchaseProcessing);
        }

        $storeTradesHolder = StoreTradesPool::Instance()->{$row->TradeID};
        $userBagHandler = new UserBagHandler($userID);
        $result = new ResultData(ErrorCode::Success);
        $result->currencies = StoreUtility::GetCurrency($userBagHandler);
        $result->remainInventory = $storeTradesHolder->remainInventory;
        return $result;
    }

}
