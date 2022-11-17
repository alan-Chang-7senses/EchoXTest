<?php

namespace Processors\Store\Purchase;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\Sessions;
use Games\Consts\ItemValue;
use Games\Consts\StoreValue;
use Games\Exceptions\StoreException;
use Games\Pools\Store\StoreTradesPool;
use Games\Store\StoreHandler;
use Games\Store\StoreUtility;
use Games\Users\ItemUtility;
use Games\Users\UserBagHandler;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\BaseProcessor;
use stdClass;

/*
 * Description of PurchaseRefresh
 * 更新目前購買格狀態，和貨幣資訊
 */

abstract class BaseRefresh extends BaseProcessor {

    protected int $nowPlat = StoreValue::PlatNone;

    abstract function PurchaseVerify(stdClass $purchaseOrders): int;

    public function Process(): ResultData {

        $orderID = InputHelper::post('orderID');

        $userID = $_SESSION[Sessions::UserID];
        $accessor = new PDOAccessor(EnvVar::DBMain);
        $row = $accessor->FromTable('StorePurchaseOrders')->
                        WhereEqual("OrderID", $orderID)->WhereEqual("UserID", $userID)->
                        WhereEqual("Plat", $this->nowPlat)->WhereEqual("Status", StoreValue::PurchaseStatusProcessing)->fetch();

        if (empty($row)) {
            throw new StoreException(StoreException::Error, ['[cause]' => "no data"]);
        }
        $storeHandler = new StoreHandler($userID);

        $storeHandler->UpdatePurchaseOrderStatus($orderID, StoreValue::PurchaseStatusVerify);

        $verifyResult = $this->PurchaseVerify($row);
        if ($verifyResult == StoreValue::PurchaseProcessRetry) {
            $storeHandler->UpdatePurchaseOrderStatus($orderID, StoreValue::PurchaseStatusProcessing);
            throw new StoreException(StoreException::PurchaseProcessing);
        } else if ($verifyResult == StoreValue::PurchaseProcessFailure) {
            $storeHandler->UpdatePurchaseOrderStatus($orderID, StoreValue::PurchaseStatusFailure);
            throw new StoreException(StoreException::PurchaseFailure);
        } else {
            $storeHandler->UpdatePurchaseOrderStatus($orderID, StoreValue::PurchaseStatusFinish);
        }

        $storeTradesHolder = StoreTradesPool::Instance()->{$row->TradeID};
        $userBagHandler = new UserBagHandler($userID);
        //加物品
        $additem = ItemUtility::GetBagItem($row->ItemID, $row->Amount);
        $userBagHandler->AddItems($additem, ItemValue::CauseStore);

        $result = new ResultData(ErrorCode::Success);
        $result->currencies = StoreUtility::GetCurrency($userBagHandler);
        $result->remainInventory = $storeTradesHolder->remainInventory;
        return $result;
    }

}
