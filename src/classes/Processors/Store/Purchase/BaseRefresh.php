<?php

namespace Processors\Store\Purchase;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Games\Consts\ItemValue;
use Games\Consts\StoreValue;
use Games\Exceptions\StoreException;
use Games\Pools\Store\StoreTradesPool;
use Games\Store\StoreHandler;
use Games\Store\StoreUtility;
use Games\Users\ItemUtility;
use Games\Users\UserBagHandler;
use Holders\ResultData;
use Processors\BaseProcessor;
use stdClass;

/*
 * Description of PurchaseRefresh
 * 更新目前購買格狀態，和貨幣資訊
 */

abstract class BaseRefresh extends BaseProcessor {

    protected int $nowPlat = StoreValue::PlatNone;
    protected int $orderID;
    protected int $userID;

    abstract function PurchaseVerify(stdClass $purchaseOrders): stdClass;

    public function HandleRefresh(): ResultData {

        $accessor = new PDOAccessor(EnvVar::DBMain);
        $row = $accessor->FromTable('StorePurchaseOrders')->
                        WhereEqual("OrderID", $this->orderID)->WhereEqual("UserID", $this->userID)->
                        WhereEqual("Plat", $this->nowPlat)->fetch();

        $userBagHandler = new UserBagHandler($this->userID);
        $tradeID = 0;
        $remainInventory = 0;
        if (!empty($row)) {
            $tradeID = $row->TradeID;
            $storeTradesHolder = StoreTradesPool::Instance()->{$row->TradeID};
            $remainInventory = $storeTradesHolder->remainInventory;

            if ($row->Status == StoreValue::PurchaseStatusVerify) {
                throw new StoreException(StoreException::PurchaseProcessing);
            } else if ($row->Status == StoreValue::PurchaseStatusFinish) {
                
            } else if ($row->Status == StoreValue::PurchaseStatusProcessing) {

                $storeHandler = new StoreHandler($this->userID);
                $storeHandler->UpdatePurchaseOrderStatus($this->orderID, StoreValue::PurchaseStatusVerify, "Verifying");

                $verifyResult = $this->PurchaseVerify($row);

                if ($verifyResult->code == StoreValue::PurchaseProcessRetry) {
                    $storeHandler->UpdatePurchaseOrderStatus($this->orderID, StoreValue::PurchaseStatusProcessing, $verifyResult->message);
                    throw new StoreException(StoreException::PurchaseProcessing);
                } else if ($verifyResult->code == StoreValue::PurchaseProcessFailure) {
                    $storeHandler->UpdatePurchaseOrderStatus($this->orderID, StoreValue::PurchaseStatusFailure, $verifyResult->message);
                    throw new StoreException(StoreException::PurchaseFailure);
                } else {
                    $storeHandler->UpdatePurchaseOrderStatus($this->orderID, StoreValue::PurchaseStatusFinish, $verifyResult->message);
                }

                if ($storeTradesHolder->remainInventory != StoreValue::InventoryNoLimit) {
                    $storeTradesHolder->remainInventory--;
                    $storeHandler->UpdateStoreTradesRemain($storeTradesHolder);
                }

                //加物品
                $additem = ItemUtility::GetBagItem($row->ItemID, $row->Amount);
                $userBagHandler->AddItems($additem, ItemValue::CauseStore);
            } else {
                throw new StoreException(StoreException::PurchaseFailure);
            }
        } else {
            throw new StoreException(StoreException::PurchaseFailure);
        }

        $result = new ResultData(ErrorCode::Success);
        $result->currencies = StoreUtility::GetCurrency($userBagHandler);
        $result->tradeID = $tradeID;
        $result->remainInventory = $remainInventory;
        return $result;
    }

}
