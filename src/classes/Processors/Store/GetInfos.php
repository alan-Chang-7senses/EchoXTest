<?php

namespace Processors\Store;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\Sessions;
use Games\Consts\StoreValue;
use Games\Pools\Store\StoreDataPool;
use Games\Store\Holders\StoreInfosHolder;
use Games\Store\Holders\StoreRefreshTimeHolder;
use Games\Store\StoreHandler;
use Games\Store\StoreUtility;
use Games\Users\UserBagHandler;
use Holders\ResultData;
use Processors\BaseProcessor;
use stdClass;

/*
 * Description of GetInfos
 */

class GetInfos extends BaseProcessor {

    public function Process(): ResultData {

        $userID = $_SESSION[Sessions::UserID];
        //get currency
        $userBagHandler = new UserBagHandler($userID);
        $responseCurrencies = [];
        foreach (StoreValue::CurrencyItemID as $currencyItemID) {
            $responseCurrencies[] = $userBagHandler->GetItemAmount($currencyItemID);
        }

        //get store
        $storHandler = new StoreHandler($userID);
        $storeIDs = $storHandler->GetStoreDatas();
        $accessor = new PDOAccessor(EnvVar::DBMain);
        $resposeStores = [];
        foreach ($storeIDs as $storeID) {
            $storeDataHolder = StoreDataPool::Instance()->{$storeID->StoreID};
            if ($storeDataHolder == false) {
                continue;
            }
            $maxFixAmount = StoreUtility::GetMaxStoreAmounts($storeDataHolder->uIStyle);
            if ($maxFixAmount == StoreValue::UINoItems) {
                continue;
            }

            $accessor->ClearCondition();
            $rowStoreInfo = $accessor->FromTable('StoreInfos')->WhereEqual('UserID', $userID)->WhereEqual('StoreID', $storeID->StoreID)->Fetch();

            $storeInfosHolder = new StoreInfosHolder();
            $autoRefreshTime = new StoreRefreshTimeHolder();
            $resetRefreshTime = new StoreRefreshTimeHolder();

            if (empty($rowStoreInfo)) {
                $storeInfosHolder->storeInfoID = StoreValue::NoStoreInfoID; //沒有資料,產出新的                                
                $storeInfosHolder->storeID = $storeID->StoreID;
                $storeInfosHolder->fixTradIDs = null;
                $storeInfosHolder->randomTradIDs = null;
                $storeInfosHolder->refreshRemainAmounts = $storeDataHolder->refreshCount;
                $autoRefreshTime->needRefresh = true;
            } else {
                $storeInfosHolder->storeInfoID = $rowStoreInfo->StoreInfoID;
                $storeInfosHolder->storeID = $storeID->StoreID;
                $storeInfosHolder->fixTradIDs = $rowStoreInfo->FixTradIDs;
                $storeInfosHolder->randomTradIDs = $rowStoreInfo->RandomTradIDs;
                $storeInfosHolder->refreshRemainAmounts = $rowStoreInfo->RefreshRemainAmounts;

                $autoRefreshTime = StoreUtility::CheckAutoRefreshTime($rowStoreInfo->UpdateTime);
                $resetRefreshTime = StoreUtility::CheckResetTime($rowStoreInfo->UpdateTime);
                if ($resetRefreshTime->needRefresh) {
                    if ($storeInfosHolder->refreshRemainAmounts == $storeDataHolder->refreshCount) {
                        $resetRefreshTime->needRefresh = false;
                    } else {
                        $storeInfosHolder->refreshRemainAmounts = $storeDataHolder->refreshCount;
                    }
                }
            }

            if ($autoRefreshTime->needRefresh) {

                if ($storeDataHolder->storeType == StoreValue::Purchase) {
                    $storeInfosHolder->fixTradIDs = $storHandler->UpdatePurchaseTrades($storeInfosHolder->fixTradIDs, $storeDataHolder->fixedGroup, $maxFixAmount);
                    $storeInfosHolder->randomTradIDs = $storHandler->UpdatePurchaseTrades($storeInfosHolder->randomTradIDs, $storeDataHolder->stochasticGroup, StoreValue::UIMaxFixItems - $maxFixAmount);
                } else if ($storeDataHolder->storeType == StoreValue::Counters) {
                    $storeInfosHolder->fixTradIDs = $storHandler->UpdateCountersTrades($storeInfosHolder->fixTradIDs, $storeDataHolder->fixedGroup, $maxFixAmount);
                    $storeInfosHolder->randomTradIDs = $storHandler->UpdateCountersTrades($storeInfosHolder->randomTradIDs, $storeDataHolder->stochasticGroup, StoreValue::UIMaxFixItems - $maxFixAmount);
                } else {
                    continue;
                }
                $storeInfosHolder->storeInfoID = $storHandler->UpdateStoreInfo($storeInfosHolder);
            } else if ($resetRefreshTime->needRefresh) {
                $storHandler->UpdateStoreInfo($storeInfosHolder);
            }

            //response
            $resposeStore = new stdClass();
            $resposeStore->storeInfoID = $storeInfosHolder->storeInfoID;
            $resposeStore->uiStyle =  $storeDataHolder->uIStyle;
            $resposeStore->autoRefreshTime = $autoRefreshTime->remainSeconds;
            $resposeStore->refreshRemain = $storeInfosHolder->refreshRemainAmounts;
            $resposeStore->refreshMax = $storeDataHolder->refreshCount;
            $resposeStore->resetRefreshTime = $resetRefreshTime->remainSeconds;
            $resposeStore->refreshCost = $storeDataHolder->refreshCost;
            $resposeStore->refreshCurrency = $storeDataHolder->refreshCostCurrency;
            $resposeStore->fixPurchase = [];
            $resposeStore->randomPurchase = [];
            $resposeStore->fixCounters = [];
            $resposeStore->randomCounters = [];

            if ($storeDataHolder->storeType == StoreValue::Purchase) {
                $resposeStore->fixPurchase = $storHandler->GetTrades(StoreValue::Purchase, $storeInfosHolder->fixTradIDs);
                $resposeStore->randomPurchase = $storHandler->GetTrades(StoreValue::Purchase, $storeInfosHolder->randomTradIDs);
            } else if ($storeDataHolder->storeType == StoreValue::Counters) {
                $resposeStore->fixCounters = $storHandler->GetTrades(StoreValue::Counters, $storeInfosHolder->fixTradIDs);
                $resposeStore->randomCounters = $storHandler->GetTrades(StoreValue::Counters, $storeInfosHolder->randomTradIDs);
            }
            $resposeStores[] = $resposeStore;
        }

        $result = new ResultData(ErrorCode::Success);
        $result->currencies = $responseCurrencies;
        $result->stores = $resposeStores;

        return $result;
    }

}
