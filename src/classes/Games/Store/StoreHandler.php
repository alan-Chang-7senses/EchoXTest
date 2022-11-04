<?php

namespace Games\Store;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\Globals;
use Games\Consts\StoreValue;
use Games\Pools\ItemInfoPool;
use Games\Pools\Store\StoreCountersPool;
use Games\Pools\Store\StorePurchasePool;
use Games\Pools\Store\StoreTradesPool;
use Games\Store\Holders\StoreInfosHolder;
use Games\Store\Holders\StorePurchaseHolder;
use Games\Store\Holders\StoreTradesHolder;
use stdClass;

/*
 * Description of StoreHandler
 */

class StoreHandler {

    private int $userID;

    public function __construct(int $userID) {
        $this->userID = $userID;
    }

    public function GetStoreDatas(): array {
        $accessor = new PDOAccessor(EnvVar::DBStatic);
        $items = $accessor->executeBindFetchAll('SELECT StoreID FROM StoreData ORDER BY StoreID ASC', []);
        return $items;
    }

    public function UpdatePurchaseTrades(int $storeType, string|null $tradIDArrays, int $groupID, int $amount): string {
        $tradIDs = empty($tradIDArrays) ? null : json_decode($tradIDArrays);
        $accessor = new PDOAccessor(EnvVar::DBStatic);
        $items = $accessor->executeBindFetchAll("SELECT * FROM (SELECT * FROM StorePurchase WHERE GROUPID = " . $groupID . " ORDER BY RAND()) AS Result GROUP BY Result.PurchaseID ORDER BY RAND()", []);
        $storeTradeIDs = [];
        $count = 0;
        foreach ($items as $item) {
            if ($count >= $amount) {
                break;
            }

            $storeTradesHolder = new StoreTradesHolder();
            if (isset($tradIDs[$count])) {
                $storeTradesHolder->tradeID = $tradIDs[$count];
                unset($tradIDs[$count]);
            } else {
                $storeTradesHolder->tradeID = StoreValue::NoTradeID;
            }

            $storeTradesHolder->storeType = $storeType;
            $storeTradesHolder->cPIndex = $item->PIndex;
            $storeTradesHolder->remainInventory = StoreValue::InventoryNoLimit;
            $storeTradeIDs[] = $this->UpdateStoreTrades($storeTradesHolder);

            $count++;
        }
        $this->ClearStoreTrade($tradIDs);

        return json_encode($storeTradeIDs);
    }

    public function UpdateCountersTrades(string|null $tradIDArrays, int $groupID, int $amount): string {
        $tradIDs = empty($tradIDArrays) ? null : json_decode($tradIDArrays);
        $accessor = new PDOAccessor(EnvVar::DBStatic);
        $items = $accessor->executeBindFetchAll("SELECT * FROM (SELECT * FROM StoreCounters WHERE GROUPID = " . $groupID . " ORDER BY RAND()) AS Result GROUP BY Result.CounterID ORDER BY RAND()", []);
        $storeTradeIDs = [];
        $count = 0;
        foreach ($items as $item) {
            if ($count >= $amount) {
                break;
            }
            $storeTradesHolder = new StoreTradesHolder();
            if (isset($tradIDs[$count])) {
                $storeTradesHolder->tradeID = $tradIDs[$count];
                unset($tradIDs[$count]);
            } else {
                $storeTradesHolder->tradeID = StoreValue::NoTradeID;
            }

            $storeTradesHolder->storeType = StoreValue::Counters;
            $storeTradesHolder->cPIndex = $item->CIndex;
            $storeTradesHolder->remainInventory = $item->Inventory;
            $storeTradeIDs[] = $this->UpdateStoreTrades($storeTradesHolder);

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

    public function UpdateStoreInfoRemain(StoreInfosHolder|stdClass $storinfo): int {
        $accessor = new PDOAccessor(EnvVar::DBMain);
        $accessor->FromTable('StoreInfos')->WhereEqual("StoreInfoID", $storinfo->storeInfoID)->Modify([
            "RefreshRemainAmounts" => $storinfo->refreshRemainAmounts,
            "UpdateTime" => (int) $GLOBALS[Globals::TIME_BEGIN]
        ]);
        return $storinfo->storeInfoID;
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

    public function UpdateStoreTrades(StoreTradesHolder|stdClass $tradeHolder): int {
        $accessor = new PDOAccessor(EnvVar::DBMain);
        $nowtime = (int) $GLOBALS[Globals::TIME_BEGIN];
        if ($tradeHolder->tradeID == StoreValue::NoTradeID) {

            // 找空閒的位置
            $rowStoreTrade = $accessor->FromTable('StoreTrades')->
                    WhereEqual('Status', StoreValue::TradeStatusIdle)->
                    Fetch();
            $accessor->ClearCondition();

            if (!empty($rowStoreTrade)) {
                $tradeHolder->tradeID = $rowStoreTrade->TradeID;
            } else {
                $accessor->FromTable('StoreTrades')->Add([
                    "UserID" => $this->userID,
                    "Status" => StoreValue::TradeStatusInUse,
                    "StoreType" => $tradeHolder->storeType,
                    "CPIndex" => $tradeHolder->cPIndex,
                    "RemainInventory" => $tradeHolder->remainInventory,
                    "UpdateTime" => $nowtime
                ]);
                return (int) $accessor->FromTable('StoreTrades')->LastInsertID();
            }
        }

        StoreTradesPool::Instance()->Save($tradeHolder->tradeID, 'Update', [
            "UserID" => $this->userID,
            "Status" => StoreValue::TradeStatusInUse,
            "StoreType" => $tradeHolder->storeType,
            "CPIndex" => $tradeHolder->cPIndex,
            "RemainInventory" => $tradeHolder->remainInventory
        ]);

        return $tradeHolder->tradeID;
    }

    public function UpdateStoreTradesRemain(StoreTradesHolder|stdClass $tradeHolder) {
        StoreTradesPool::Instance()->Save($tradeHolder->tradeID, 'Update', [
            "RemainInventory" => $tradeHolder->remainInventory
        ]);
    }

    public function GetTrades(int $storeType, string|null $tradIDArrays): array {
        $tradIDs = ($tradIDArrays != null) ? json_decode($tradIDArrays) : [];
        $result = [];
        foreach ($tradIDs as $tradID) {
            $storeTradesHolder = StoreTradesPool::Instance()->{$tradID};
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
                $itemInfo = ItemInfoPool::Instance()->{ $storePurchaseHolder->itemID};

                $tradeInfo->itemID = $storePurchaseHolder->itemID;
                $tradeInfo->amount = $storePurchaseHolder->amount;
                $tradeInfo->icon = $itemInfo->Icon;
                $tradeInfo->name = $itemInfo->ItemName;
                $tradeInfo->IAP = $storePurchaseHolder->iAP;
                $tradeInfo->IAB = $storePurchaseHolder->iAB;
            } else if ($storeType == StoreValue::Counters) {
                $storeCountersHolder = StoreCountersPool::Instance()->{$storeTradesHolder->cPIndex};
                $itemInfo = ItemInfoPool::Instance()->{ $storeCountersHolder->itemID};

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

    public function CreatPurchaseOrder(StorePurchaseHolder|stdClass $storePurchaseHolder, int $tradeID, int $device): int {

        $accessor = new PDOAccessor(EnvVar::DBMain);
        $nowtime = (int) $GLOBALS[Globals::TIME_BEGIN];

        $accessor->FromTable('StorePurchaseOrders')->Add([
            "UserID" => $this->userID,
            "TradeID" => $tradeID,
            "Device" => $device,
            "ItemID" => $storePurchaseHolder->itemID,
            "Amount" => $storePurchaseHolder->amount,
            "Status" => StoreValue::PurchaseStatusProcessing,
            "CreateTime" => $nowtime,
            "UpdateTime" => $nowtime
        ]);
        return (int) $accessor->LastInsertID();
    }

    public function UpdatePurchaseOrderStatus(int $orderID, int $status) {
        $accessor = new PDOAccessor(EnvVar::DBMain);

        $accessor->FromTable('StorePurchaseOrders')->WhereEqual("OrderID", $orderID)->Modify([
            "Status" => $status,
            "UpdateTime" => (int) $GLOBALS[Globals::TIME_BEGIN]
        ]);
    }

    public function FinishPurchaseOrder(int $orderID, string $orderNO, string $usdAmount, string $payAmount, string $payCurrency) {

        $accessor = new PDOAccessor(EnvVar::DBMain);
        $accessor->FromTable('StorePurchaseOrders')->WhereEqual("OrderID", $orderID)->Modify([
            "Status" => StoreValue::PurchaseStatusFinish,
            "OrderNo" => $orderNO,
            "UsdAmount" => $usdAmount,
            "PayAmount" => $payAmount,
            "PayCurrency" => $payCurrency,
            "UpdateTime" => (int) $GLOBALS[Globals::TIME_BEGIN]
        ]);
    }

}
