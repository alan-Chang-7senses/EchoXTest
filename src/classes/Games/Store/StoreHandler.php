<?php

namespace Games\Store;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\Globals;
use Games\Consts\StoreValue;
use Games\Exceptions\StoreException;
use Games\Pools\ItemInfoPool;
use Games\Pools\Store\StoreCountersPool;
use Games\Pools\Store\StoreProductInfoPool;
use Games\Pools\Store\StorePurchasePool;
use Games\Pools\Store\StoreTradesPool;
use Games\Store\Holders\StoreInfosHolder;
use Games\Store\Holders\StorePurchaseHolder;
use Games\Store\Holders\StoreRefreshTimeHolder;
use Games\Store\Holders\StoreTradesHolder;
use Generators\DataGenerator;
use stdClass;

/*
 * Description of StoreHandler
 */

class StoreHandler {

    private int $userID;

    public function __construct(int $userID) {
        $this->userID = $userID;
    }

    public function GetRefreshTime(): StoreRefreshTimeHolder|false {
        $accessor = new PDOAccessor(EnvVar::DBMain);
        $rowStoreUserInfos = $accessor->FromTable('StoreUserInfos')->SelectExpr('UserID, AutoRefreshTime')->WhereEqual('UserID', $this->userID)->Fetch();
        if ($rowStoreUserInfos == false) {
            return false;
        }

        $autoRefreshTime = StoreUtility::CheckAutoRefreshTime($rowStoreUserInfos->AutoRefreshTime);
        return $autoRefreshTime;
    }

    public function AddRefreshTime(int|string $device, int|string $plat, string $isoCurrency) {

        $nowTime = (int) $GLOBALS[Globals::TIME_BEGIN];
        $accessor = new PDOAccessor(EnvVar::DBMain);
        $accessor->FromTable('StoreUserInfos')->Add([
            "UserID" => $this->userID,
            "Device" => $device,
            "Plat" => $plat,
            "ISOCurrency" => $isoCurrency,
            "AutoRefreshTime" => $nowTime
        ]);
    }

    public function ModifyCurrency(string $isoCurrency, int|string $device) {
        $accessor = new PDOAccessor(EnvVar::DBMain);
        $storeUserInfos = $accessor->FromTable('StoreUserInfos')->SelectExpr('UserID, ISOCurrency, Device')->WhereEqual('UserID', $this->userID)->Fetch();
        if (($storeUserInfos->ISOCurrency !== $isoCurrency) ||
                ($storeUserInfos->Device !== $device)
        ) {
            $accessor->Modify([
                "ISOCurrency" => $isoCurrency,
                "Device" => $device
            ]);
        }
    }

    public function UpdateRefreshTime(StoreRefreshTimeHolder $autorefresh) {
        if ($autorefresh->needRefresh) {
            $accessor = new PDOAccessor(EnvVar::DBMain);
            $accessor->FromTable('StoreUserInfos')->WhereEqual("UserID", $this->userID)->Modify([
                "AutoRefreshTime" => (int) $GLOBALS[Globals::TIME_BEGIN]
            ]);
        }
    }

    public function UpdatePurchaseTrades(int $storeID, int $storeType, string|null $tradIDArrays, int $groupID, int $amount): string {

        if ($groupID == StoreValue::NoStoreGroup) {
            return "";
        }
        $tradIDs = empty($tradIDArrays) ? null : json_decode($tradIDArrays);
        $accessor = new PDOAccessor(EnvVar::DBStatic);
        $purchaseGroup = $accessor->executeBindFetch("SELECT PurchaseID FROM StorePurchase WHERE GROUPID = " . $groupID . " GROUP BY PurchaseID ORDER BY RAND()", []);
        if ($purchaseGroup === false) {
            throw new StoreException(StoreException::Error, ['[cause]' => "L89" . $groupID]);
        }

        $storeTradeIDs = [];
        $count = 0;
        $items = $accessor->ClearAll()->FromTable("StorePurchase")->WhereEqual("GROUPID", $groupID)->WhereEqual("PurchaseID", $purchaseGroup->PurchaseID)->OrderBy("", "RAND()")->FetchAll();
        if (!isset($items)) {
            throw new StoreException(StoreException::Error, ['[cause]' => "L96:" . $purchaseGroup->PurchaseID]);
        }

        foreach ($items as $item) {
            if ($count >= $amount) {
                break;
            }
            $storeTradesHolder = new StoreTradesHolder();
            $storeTradesHolder->tradeID = StoreValue::NoTradeID;
            $storeTradesHolder->storeID = $storeID;
            $storeTradesHolder->storeType = $storeType;
            $storeTradesHolder->cPIndex = $item->PIndex;
            $storeTradesHolder->remainInventory = StoreValue::InventoryNoLimit;
            $storeTradeIDs[] = $this->AddStoreTrades($storeTradesHolder);

            $count++;
        }
        $this->ClearStoreTrade($tradIDs);

        return json_encode($storeTradeIDs);
    }

    public function UpdateCountersTrades(int $storeID, string|null $tradIDArrays, int $groupID, int $amount): string {

        if ($groupID == StoreValue::NoStoreGroup) {
            return "";
        }
        $tradIDs = empty($tradIDArrays) ? null : json_decode($tradIDArrays);
        $accessor = new PDOAccessor(EnvVar::DBStatic);
        $countersGroup = $accessor->executeBindFetch("SELECT CounterID FROM StoreCounters WHERE GROUPID =" . $groupID . " GROUP BY CounterID ORDER BY RAND()", []);
        if (!isset($countersGroup)) {
            throw new StoreException(StoreException::Error, ['[cause]' => "L127:" . $groupID]);
        }

        $items = $accessor->ClearAll()->FromTable("StoreCounters")->WhereEqual("GROUPID", $groupID)->WhereEqual("CounterID", $countersGroup->CounterID)->OrderBy("", "RAND()")->FetchAll();
        if (!isset($items)) {
            throw new StoreException(StoreException::Error, ['[cause]' => "L132:" . $countersGroup->CounterID]);
        }

        $storeTradeIDs = [];
        $count = 0;
        foreach ($items as $item) {
            if ($count >= $amount) {
                break;
            }
            $storeTradesHolder = new StoreTradesHolder();
            $storeTradesHolder->tradeID = StoreValue::NoTradeID;
            $storeTradesHolder->storeID = $storeID;
            $storeTradesHolder->storeType = StoreValue::TypeCounters;
            $storeTradesHolder->cPIndex = $item->CIndex;
            $storeTradesHolder->remainInventory = $item->Inventory;
            $storeTradeIDs[] = $this->AddStoreTrades($storeTradesHolder);
            $count++;
        }
        $this->ClearStoreTrade($tradIDs);

        return json_encode($storeTradeIDs);
    }

    public function UpdateStoreInfo(StoreInfosHolder|stdClass $storinfo): int {
        $accessor = new PDOAccessor(EnvVar::DBMain);
        $nowtime = (int) $GLOBALS[Globals::TIME_BEGIN];
        if ($storinfo->storeInfoID == StoreValue::NoStoreInfoID) {
            $accessor->FromTable('StoreInfos')->Add([
                "UserID" => $this->userID,
                "StoreID" => $storinfo->storeID,
                "FixTradIDs" => $storinfo->fixTradIDs,
                "RandomTradIDs" => $storinfo->randomTradIDs,
                "RefreshRemainAmounts" => $storinfo->refreshRemainAmounts,
                "CreateTime" => $nowtime,
                "UpdateTime" => $nowtime
            ]);
            return (int) $accessor->LastInsertID();
        } else {
            $accessor->FromTable('StoreInfos')->WhereEqual("StoreInfoID", $storinfo->storeInfoID)->Modify([
                "FixTradIDs" => $storinfo->fixTradIDs,
                "RandomTradIDs" => $storinfo->randomTradIDs,
                "RefreshRemainAmounts" => $storinfo->refreshRemainAmounts,
                "UpdateTime" => $nowtime
            ]);
            return $storinfo->storeInfoID;
        }
    }

    private function ClearStoreTrade(array|null $tradIDs) {
        if ($tradIDs == null) {
            return;
        }

        foreach ($tradIDs as $tradID) {
            StoreTradesPool::Instance()->Save($tradID, 'Update', [
                "Status" => StoreValue::TradeStatusIdle,
            ]);
        }
    }

    private function AddStoreTrades(StoreTradesHolder|stdClass $tradeHolder): int {
        $accessor = new PDOAccessor(EnvVar::DBMain);
        $nowtime = (int) $GLOBALS[Globals::TIME_BEGIN];
        $accessor->FromTable('StoreTrades')->Add([
            "UserID" => $this->userID,
            "StoreID" => $tradeHolder->storeID,
            "Status" => StoreValue::TradeStatusInUse,
            "StoreType" => $tradeHolder->storeType,
            "CPIndex" => $tradeHolder->cPIndex,
            "RemainInventory" => $tradeHolder->remainInventory,
            "UpdateTime" => $nowtime
        ]);
        return (int) $accessor->FromTable('StoreTrades')->LastInsertID();
    }

    public function UpdateStoreTradesRemain(StoreTradesHolder|stdClass $tradeHolder) {
        StoreTradesPool::Instance()->Save($tradeHolder->tradeID, 'Update', [
            "RemainInventory" => $tradeHolder->remainInventory
        ]);
    }

    public function GetTrades(int $storeType, string $currency, string|null $tradIDArrays): array {
        $tradIDs = ($tradIDArrays != null) ? json_decode($tradIDArrays) : [];
        $result = [];
        foreach ($tradIDs as $tradID) {
            $storeTradesHolder = StoreTradesPool::Instance()->{$tradID};

            if ($storeTradesHolder == false) {
                throw new StoreException(StoreException::Error, ['[cause]' => 'L217']);
            }

            $tradeInfo = new stdClass();
            $tradeInfo->tradID = $tradID;

            if ($storeTradesHolder->storeType != $storeType) {
                //error
                continue;
            }

            if ($storeTradesHolder->status != StoreValue::TradeStatusInUse) {
                //error
                continue;
            }

            if (StoreUtility::IsPurchaseStore($storeType)) {
                $storePurchaseHolder = StorePurchasePool::Instance()->{$storeTradesHolder->cPIndex};
                if ($storePurchaseHolder == false) {
                    throw new StoreException(StoreException::Error, ['[cause]' => "PIndex " . $storeTradesHolder->cPIndex]);
                }

                $itemInfo = ItemInfoPool::Instance()->{ $storePurchaseHolder->itemID};
                $tradeInfo->itemID = $storePurchaseHolder->itemID;
                $tradeInfo->amount = $storePurchaseHolder->amount;
                $tradeInfo->icon = $itemInfo->Icon;
                $tradeInfo->name = $itemInfo->ItemName;
                $tradeInfo->product = $storePurchaseHolder->productID;

                if ($storeType == StoreValue::TypeMyCard) {
                    $storeProductInfoModels = StoreProductInfoPool::Instance()->{$storePurchaseHolder->productID};
                    if ((!empty($storeProductInfoModels)) && (property_exists($storeProductInfoModels, $currency))) {
                        $storeProductInfoModel = $storeProductInfoModels->{$currency};
                        $tradeInfo->multiNo = $storeProductInfoModel->MultiNo;
                        $tradeInfo->price = $storeProductInfoModel->Price;
                    } else {
                        throw new StoreException(StoreException::Error, ['[cause]' => 'L255 ' . $storePurchaseHolder->productID]);
                    }
                }
            } else if ($storeType == StoreValue::TypeCounters) {
                $storeCountersHolder = StoreCountersPool::Instance()->{$storeTradesHolder->cPIndex};

                if ($storeCountersHolder == false) {
                    throw new StoreException(StoreException::Error, ['[cause]' => "CIndex " . $storeTradesHolder->cPIndex]);
                }

                $itemInfo = ItemInfoPool::Instance()->{ $storeCountersHolder->itemID};
                if ($itemInfo == false) {
                    throw new StoreException(StoreException::Error, ['[cause]' => 'ItemID does not exist ' . $storeCountersHolder->itemID]);
                }

                $tradeInfo->itemID = $storeCountersHolder->itemID;
                $tradeInfo->amount = $storeCountersHolder->amount;
                $tradeInfo->icon = $itemInfo->Icon;
                $tradeInfo->name = $itemInfo->ItemName;
                $tradeInfo->remainInventory = $storeTradesHolder->remainInventory;
                $tradeInfo->price = $storeCountersHolder->price;
                $tradeInfo->currency = $storeCountersHolder->currency;
                $tradeInfo->promotion = $storeCountersHolder->promotion;
            } else {
                //error
                continue;
            }
            $result[] = $tradeInfo;
        }
        return $result;
    }

    public function CreatPurchaseOrder(StorePurchaseHolder|stdClass $storePurchaseHolder, int $tradeID, int $plat): string {

        $accessor = new PDOAccessor(EnvVar::DBMain);
        $nowtime = (int) $GLOBALS[Globals::TIME_BEGIN];

        $orderid = DataGenerator::GuidV4();
        $accessor->FromTable('StorePurchaseOrders')->Add([
            "OrderID" => $orderid,
            "UserID" => $this->userID,
            "TradeID" => $tradeID,
            "ProductID" => $storePurchaseHolder->productID,
            "ItemID" => $storePurchaseHolder->itemID,
            "Amount" => $storePurchaseHolder->amount,
            "Plat" => $plat,
            "Status" => StoreValue::PurchaseStatusProcessing,
            "Receipt" => "",
            "CreateTime" => $nowtime,
            "UpdateTime" => $nowtime
        ]);
        return $orderid;
    }

    public function UpdatePurchaseOrderStatus(string $orderID, int $status, string $message) {
        self::UpdatePurchaseOrderStatusStatic($orderID, $status, $message);
    }

    public static function UpdatePurchaseOrderStatusStatic(string $orderID, int $status, string $message) {
        $accessor = new PDOAccessor(EnvVar::DBMain);
        $accessor->FromTable('StorePurchaseOrders')->WhereEqual("OrderID", $orderID)->Modify([
            "Status" => $status,
            "Message" => $message,
            "UpdateTime" => (int) $GLOBALS[Globals::TIME_BEGIN]
        ]);
    }

//    public function VerrifyProduct(stdclass|StorePurchaseOrdersHolder $storePurchaseOrdersHolder): bool {
//
//        if ($storePurchaseOrdersHolder->Status == StoreValue::PurchaseStatusFinish) {
//            return true;
//        }
//
//        if ($storePurchaseOrdersHolder->Status != StoreValue::PurchaseStatusProcessing) {
//            return false;
//        }
//
//        if (empty($storePurchaseOrdersHolder->Receipt)) {
//            return false;
//        }
//
//        if ($storePurchaseOrdersHolder->Plat == StoreValue::PlatMyCard) {
//            $result = PurchaseUtility::MyCardVerify($this->userID, $storePurchaseOrdersHolder->Receipt);
//        } else {
//            return false;
//        }
//
//        $orderID = $storePurchaseOrdersHolder->OrderID;
//
//        if ($result == StoreValue::PurchaseVerifySuccess) {
//            $this->UpdatePurchaseOrderStatus($orderID, StoreValue::PurchaseStatusFinish);
//            //加物品
//            $userBagHandler = new UserBagHandler($this->userID);
//            $additem = ItemUtility::GetBagItem($storePurchaseOrdersHolder->ItemID, $storePurchaseOrdersHolder->Amount);
//            $userBagHandler->AddItems($additem, ItemValue::CauseStore);
//        } else if ($result == StoreValue::PurchaseVerifyFailure) {
//            $this->UpdatePurchaseOrderStatus($orderID, StoreValue::PurchaseStatusFailure);
//        }
//        return true;
//    }
}
