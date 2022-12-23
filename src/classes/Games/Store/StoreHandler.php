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

    public function UpdatePurchaseTrades(int $storeID, int $storeType, int $isFix, int $groupID, int $amount): string {

        if ($groupID == StoreValue::NoStoreGroup) {
            return "";
        }
        $tradIDs = $this->GetTradeIDs($storeID, $isFix);

        $accessor = new PDOAccessor(EnvVar::DBStatic);
        $items = $accessor->executeBindFetchAll("SELECT * FROM (
            SELECT * FROM StorePurchase v1 INNER JOIN
            (SELECT PurchaseID AS x FROM StorePurchase WHERE GroupID = :GroupID GROUP BY PurchaseID ORDER BY RAND()  LIMIT 1) as v2
            ON v1.PurchaseID = v2.x  ORDER BY RAND() LIMIT :Amount
            ) v3 ORDER BY Pindex", [
            "GroupID" => $groupID,
            "Amount" => $amount
        ]);

        $storeTradeIDs = [];
        $idleTradeIDs = []; // 儲值商品因為有第三方的問題，所以不取代 TradeIDs
        $this->GetIdleTradeIds($idleTradeIDs, count($items));

        if (empty($items)) {
            throw new StoreException(StoreException::Error, ['[cause]' => "L96:" . $groupID]);
        }

        foreach ($items as $item) {
            $storeTradesHolder = new StoreTradesHolder();
            $storeTradesHolder->tradeID = $this->UseIdleTradeIds($idleTradeIDs);
            $storeTradesHolder->storeID = $storeID;
            $storeTradesHolder->storeType = $storeType;
            $storeTradesHolder->isFix = $isFix;
            $storeTradesHolder->cPIndex = $item->PIndex;
            $storeTradesHolder->remainInventory = StoreValue::InventoryNoLimit;
            $storeTradeIDs[] = $this->UpdateStoreTrades($storeTradesHolder);
        }
        $this->ResetTradeStatus($tradIDs, StoreValue::TradeStatusSeal); //儲值要封存不能釋放

        return json_encode($storeTradeIDs);
    }

    public function UpdateCountersTrades(int $storeID, int $isFix, int $groupID, int $amount): string {

        if ($groupID == StoreValue::NoStoreGroup) {
            return "";
        }
        //$tradIDs = empty($tradIDArrays) ? null : json_decode($tradIDArrays);
        $tradIDs = $this->GetTradeIDs($storeID, $isFix);
        $accessor = new PDOAccessor(EnvVar::DBStatic);

        $items = $accessor->executeBindFetchAll("SELECT * FROM (
            SELECT * FROM StoreCounters v1 INNER JOIN
            (SELECT CounterID AS TempID FROM StoreCounters WHERE GroupID = :GroupID GROUP BY CounterID ORDER BY RAND()  LIMIT 1) as v2
            ON v1.CounterID = v2.TempID  ORDER BY RAND() LIMIT :Amount
            ) v3 ORDER BY Cindex", [
            "GroupID" => $groupID,
            "Amount" => $amount
        ]);

        if (empty($items)) {
            throw new StoreException(StoreException::Error, ['[cause]' => "L132:" . $groupID]);
        }

        $storeTradeIDs = [];
        $this->GetIdleTradeIds($tradIDs, count($items) - count($tradIDs)); //一般商品可取代 tradIDs

        foreach ($items as $item) {
            $storeTradesHolder = new StoreTradesHolder();
            $storeTradesHolder->tradeID = $this->UseIdleTradeIds($tradIDs);
            $storeTradesHolder->storeID = $storeID;
            $storeTradesHolder->storeType = StoreValue::TypeCounters;
            $storeTradesHolder->isFix = $isFix;
            $storeTradesHolder->cPIndex = $item->CIndex;
            $storeTradesHolder->remainInventory = $item->Inventory;
            $storeTradeIDs[] = $this->UpdateStoreTrades($storeTradesHolder);
        }
        $this->ResetTradeStatus($tradIDs, StoreValue::TradeStatusIdle);

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

    public function GetTradeIDs(int $storeID, int $isFix): array {
        $accessor = new PDOAccessor(EnvVar::DBMain);
        $myTradeIds = $accessor->SelectExpr('TradeID, UserID, Status, StoreID, IsFix')->FromTable('StoreTrades')->
                WhereEqual("UserID", $this->userID)->WhereEqual("Status", StoreValue::TradeStatusInUse)->
                WhereEqual("StoreID", $storeID)->WhereEqual("IsFix", $isFix)->
                FetchAll();

        $oldMyTradeIds = [];
        foreach ($myTradeIds as $myTradeId) {
            $oldMyTradeIds[] = $myTradeId->TradeID;
        }

        return $oldMyTradeIds;
    }

    private function GetIdleTradeIds(array &$oldTradeIds, int $needAmount) {
        if ($needAmount <= 0) {
            return;
        }

        $accessor = new PDOAccessor(EnvVar::DBMain);
        $needTradeIds = $accessor->SelectExpr('`TradeID`, `Status`')->FromTable('StoreTrades')->
                        WhereEqual('Status', StoreValue::TradeStatusIdle)->
                        Limit($needAmount)->FetchAll();

        foreach ($needTradeIds as $needTradeId) {
            $oldTradeIds[] = $needTradeId->TradeID;
        }
    }

    private function UseIdleTradeIds(array &$tradeIds): int {
        $tradeID = array_shift($tradeIds);
        if (empty($tradeID)) {
            return StoreValue::NoTradeID;
        } else {
            return $tradeID;
        }
    }

    private function ResetTradeStatus(array|null $tradeIDs, int $tradeStatus) {
        if ($tradeIDs == null) {
            return;
        }

        foreach ($tradeIDs as $tradeID) {
            StoreTradesPool::Instance()->Save($tradeID, 'Update', [
                "Status" => $tradeStatus,
                "UpdateTime" => (int) $GLOBALS[Globals::TIME_BEGIN]
            ]);
        }
    }

    private function UpdateStoreTrades(StoreTradesHolder|stdClass $tradeHolder): int {
        $accessor = new PDOAccessor(EnvVar::DBMain);
        $nowtime = (int) $GLOBALS[Globals::TIME_BEGIN];
        $bind = [
            "UserID" => $this->userID,
            "StoreID" => $tradeHolder->storeID,
            "Status" => StoreValue::TradeStatusInUse,
            "StoreType" => $tradeHolder->storeType,
            "IsFix" => $tradeHolder->isFix,
            "CPIndex" => $tradeHolder->cPIndex,
            "RemainInventory" => $tradeHolder->remainInventory,
            "UpdateTime" => $nowtime
        ];
        if ($tradeHolder->tradeID == StoreValue::NoTradeID) {
            // 加入新資料
            $accessor->FromTable('StoreTrades')->Add($bind);
            return (int) $accessor->FromTable('StoreTrades')->LastInsertID();
        } else {
            // 更新資料
            StoreTradesPool::Instance()->Save($tradeHolder->tradeID, 'Update', $bind);
            return $tradeHolder->tradeID;
        }
    }

    public function UpdateStoreTradesRemain(StoreTradesHolder|stdClass $tradeHolder) {
        StoreTradesPool::Instance()->Save($tradeHolder->tradeID, 'Update', [
            "RemainInventory" => $tradeHolder->remainInventory
        ]);
    }

    public function GetTrades(int $storeType, string $currency, string|null $tradIDArrays, int $storeID, int $isFix): array {
        $tradIDs = ($tradIDArrays != null) ? json_decode($tradIDArrays) : $this->GetTradeIDs($storeID, $isFix);
        $result = [];
        if ($tradIDs === null) {
            return $result;
        }

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

}
