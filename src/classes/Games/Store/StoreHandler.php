<?php

namespace Games\Store;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\Globals;
use Games\Consts\StoreValue;
use Games\Pools\Store\StoreTradesPool;
use Games\Store\Holders\StoreInfosHolder;
use Games\Store\Holders\StoreTradesHolder;

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

    public function UpdatePurchaseTrades(string|null $tradIDArrays, int $groupID, int $amount): string {

        $tradIDs = ($tradIDArrays != null) ? json_decode($tradIDArrays) : null;
        
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

            $storeTradesHolder->storeType = StoreValue::Purchase;
            $storeTradesHolder->cPIndex = $item->PIndex;
            $storeTradesHolder->remainInventory = StoreValue::InventoryNoLimit;
            $storeTradeIDs[] = $this->UpdateStoreTrades($storeTradesHolder);

            $count++;
        }
        $this->ClearStoreTrade($tradIDs);

        return json_encode($storeTradeIDs);
    }

    public function UpdateCountersTrades(string|null $tradIDArrays, int $groupID, int $amount): string {
        $tradIDs = ($tradIDArrays != null) ? json_decode($tradIDArrays) : null;
        
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

    public function UpdateStoreInfo(StoreInfosHolder $storinfo): int {
        $accessor = new PDOAccessor(EnvVar::DBMain);
        $nowtime = (int) $GLOBALS[Globals::TIME_BEGIN];
        if ($storinfo->storeInfoID == StoreValue::NoStoreInfoID) {
            $accessor->FromTable('StoreInfos')->Add([
                "UserID" => $this->userID,
                "StoreID" => $storinfo->storeID,
                "FixTradIDs" => $storinfo->fixTradIDs,
                "RandomTradIDs" => $storinfo->randomTradIDs,
                "RemainRefreshTimes" => $storinfo->remainRefreshTimes,
                "CreateTime" => $nowtime,
                "UpdateTime" => $nowtime
            ]);
            return (int) $accessor->LastInsertID();
        } else {
            $accessor->FromTable('StoreInfos')->WhereEqual("StoreInfoID", $storinfo->storeInfoID)->Modify([
                "FixTradIDs" => $storinfo->fixTradIDs,
                "RandomTradIDs" => $storinfo->randomTradIDs,
                "RemainRefreshTimes" => $storinfo->remainRefreshTimes,
                "UpdateTime" => (int) $GLOBALS[Globals::TIME_BEGIN]
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
                "Status" => StoreValue::TradeIdle,
            ]);
        }
    }

    public function UpdateStoreTrades(StoreTradesHolder $tradeHolder): int {
        $accessor = new PDOAccessor(EnvVar::DBMain);
        $nowtime = (int) $GLOBALS[Globals::TIME_BEGIN];
        if ($tradeHolder->tradeID == StoreValue::NoTradeID) {

            // 找空閒的位置
            $rowStoreTrade = $accessor->FromTable('StoreTrades')->
                    WhereEqual('Status', StoreValue::TradeIdle)->
                    Fetch();
            $accessor->ClearCondition();

            if (!empty($rowStoreTrade)) {
                $tradeHolder->tradeID = $rowStoreTrade->TradeID;
            } else {
                $accessor->FromTable('StoreTrades')->Add([
                    "UserID" => $this->userID,
                    "Status" => StoreValue::TradeInUse,
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
            "Status" => StoreValue::TradeInUse,
            "StoreType" => $tradeHolder->storeType,
            "CPIndex" => $tradeHolder->cPIndex,
            "RemainInventory" => $tradeHolder->remainInventory
        ]);

        return $tradeHolder->tradeID;
    }

    public function CheckRefresh(int $datetime): bool {
        return true;
    }

}
