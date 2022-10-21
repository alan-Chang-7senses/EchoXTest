<?php

namespace Processors\Store;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\Sessions;
use Games\Consts\ItemValue;
use Games\Consts\StoreValue;
use Games\Exceptions\StoreException;
use Games\Pools\Store\StoreDataPool;
use Games\Store\StoreHandler;
use Games\Store\StoreUtility;
use Games\Users\UserBagHandler;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\BaseProcessor;

/*
 * Description of Refresh
 */

class Refresh extends BaseProcessor {

    public function Process(): ResultData {


        $storeInfoID = InputHelper::post('storeInfoID');
        $userID = $_SESSION[Sessions::UserID];

        $accessor = new PDOAccessor(EnvVar::DBMain);
        $rowStoreInfo = $accessor->FromTable('StoreInfos')->WhereEqual('StoreInfoID', $storeInfoID)->Fetch();
        $storeInfosHolder = StoreUtility::GetStoreInfosHolder($rowStoreInfo);

        if ($userID != $storeInfosHolder->userID) {
            throw new StoreException(StoreException::Error, ['[des]' => "users"]);
        }

        if ($storeInfosHolder->refreshRemainAmounts == StoreValue::RefreshRemainEmpty) {
            throw new StoreException(StoreException::NoRefreshCount);
        }

        $autoRefreshTime = StoreUtility::CheckAutoRefreshTime($storeInfosHolder->updateTime);
        if ($autoRefreshTime->needRefresh) {
            throw new StoreException(StoreException::Refreshed);
        }

        $storeDataHolder = StoreDataPool::Instance()->{$storeInfosHolder->storeID};
        $maxFixAmount = StoreUtility::GetMaxStoreAmounts($storeDataHolder->uIStyle);
        if ($maxFixAmount == StoreValue::UINoItems) {
            throw new StoreException(StoreException::Error, ['[des]' => "table"]);
        }

        if ($storeDataHolder->refreshCostCurrency != StoreValue::CurrencyFree) {
            $itemID = StoreUtility::GetCurrencyItemID($storeDataHolder->refreshCostCurrency);
            $userBagHandler = new UserBagHandler($userID);
            if ($userBagHandler->DecItemByItemID($itemID, $storeDataHolder->refreshCost, ItemValue::CauseStore) == false) {
                throw new StoreException(StoreException::NotEnoughCurrency); //錢不夠
            }
        }

        $storeInfosHolder->refreshRemainAmounts--;
        $storeHandler = new StoreHandler($userID);
        if ($storeDataHolder->storeType == StoreValue::Purchase) {
            $storeInfosHolder->randomTradIDs = $storeHandler->UpdatePurchaseTrades($storeInfosHolder->randomTradIDs, $storeDataHolder->stochasticGroup, StoreValue::UIMaxFixItems - $maxFixAmount);
        } else if ($storeDataHolder->storeType == StoreValue::Counters) {
            $storeInfosHolder->randomTradIDs = $storeHandler->UpdateCountersTrades($storeInfosHolder->randomTradIDs, $storeDataHolder->stochasticGroup, StoreValue::UIMaxFixItems - $maxFixAmount);
        }
        $storeInfosHolder->storeInfoID = $storeHandler->UpdateStoreInfo($storeInfosHolder);

        //response
        $result = new ResultData(ErrorCode::Success);
        $result->refreshRemain = $storeInfosHolder->refreshRemainAmounts;
        $result->currencies = StoreUtility::GetCurrency($userBagHandler);
        $result->randomPurchase = [];
        $result->randomCounters = [];
        if ($storeDataHolder->storeType == StoreValue::Purchase) {
            $result->randomPurchase = $storeHandler->GetTrades(StoreValue::Purchase, $storeInfosHolder->randomTradIDs);
        } else if ($storeDataHolder->storeType == StoreValue::Counters) {
            $result->randomCounters = $storeHandler->GetTrades(StoreValue::Counters, $storeInfosHolder->randomTradIDs);
        }
        return $result;
    }

}
