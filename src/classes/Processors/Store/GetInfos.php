<?php

namespace Processors\Store;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\Globals;
use Consts\Sessions;
use Games\Consts\StoreValue;
use Games\Pools\Store\StoreDataPool;
use Games\Store\StoreHandler;
use Games\Store\StoreUtility;
use Games\Users\UserBagHandler;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\BaseProcessor;
use stdClass;

/*
 * Description of GetInfos
 */

class GetInfos extends BaseProcessor {

    public function Process(): ResultData {

        $userID = $_SESSION[Sessions::UserID];
        $device = InputHelper::post('device');
        $plat = InputHelper::post('plat');
        $currency = InputHelper::post('currency');

        $storeHandler = new StoreHandler($userID);
        $autoRefreshTime = $storeHandler->GetRefreshTime();
        if ($autoRefreshTime == false) {
            $autoRefreshTime = StoreUtility::CheckAutoRefreshTime((int) $GLOBALS[Globals::TIME_BEGIN]);
            $autoRefreshTime->needRefresh = true;
            $storeHandler->AddRefreshTime($device, $plat, $currency);
        } else {
            $storeHandler->UpdateRefreshTime($autoRefreshTime);
        }
        
        $storeHandler->ModifyCurrency($currency);

        //get store
        $accessorStatic = new PDOAccessor(EnvVar::DBStatic);
        $storeDatas = $accessorStatic->executeBindFetchAll('SELECT StoreID FROM StoreData ORDER BY StoreID ASC', []);

        $resposeStores = [];
        $accessor = new PDOAccessor(EnvVar::DBMain);
        foreach ($storeDatas as $storeData) {
            $storeID = $storeData->StoreID;
            $storeDataHolder = StoreDataPool::Instance()->{$storeID};
            if ($storeDataHolder == false) {
                continue;
            }

            if (($storeDataHolder->storeType == StoreValue::TypeMyCard) &&
                    (getenv(EnvVar::MyCardSwitch) != 'true')) {
                continue;
            }

            $nowPlat = StoreUtility::GetPurchasePlat($storeDataHolder->storeType);
            if (($nowPlat != StoreValue::PlatNone) && ($nowPlat != $plat)) {
                continue;
            }

            $maxFixAmount = StoreUtility::GetMaxStoreAmounts($storeDataHolder->uIStyle);
            if ($maxFixAmount == StoreValue::UIUnset) {
                continue;
            }

            $accessor->ClearCondition();
            $rowStoreInfo = $accessor->FromTable('StoreInfos')->WhereEqual('UserID', $userID)->WhereEqual('StoreID', $storeID)->Fetch();
            $storeInfosHolder = StoreUtility::GetStoreInfosHolder($rowStoreInfo);
            $storeInfosHolder->storeID = $storeID;

            if ($autoRefreshTime->needRefresh || empty($rowStoreInfo)) {

                if (StoreUtility::IsPurchaseStore($storeDataHolder->storeType)) {
                    $storeInfosHolder->fixTradIDs = $storeHandler->UpdatePurchaseTrades($storeID, $storeDataHolder->storeType, $storeInfosHolder->fixTradIDs, $storeDataHolder->fixedGroup, $maxFixAmount);
                    $storeInfosHolder->randomTradIDs = $storeHandler->UpdatePurchaseTrades($storeID, $storeDataHolder->storeType, $storeInfosHolder->randomTradIDs, $storeDataHolder->stochasticGroup, StoreValue::UIMaxFixItems - $maxFixAmount);
                } else if ($storeDataHolder->storeType == StoreValue::TypeCounters) {
                    $storeInfosHolder->fixTradIDs = $storeHandler->UpdateCountersTrades($storeID, $storeInfosHolder->fixTradIDs, $storeDataHolder->fixedGroup, $maxFixAmount);
                    $storeInfosHolder->randomTradIDs = $storeHandler->UpdateCountersTrades($storeID, $storeInfosHolder->randomTradIDs, $storeDataHolder->stochasticGroup, StoreValue::UIMaxFixItems - $maxFixAmount);
                } else {
                    continue;
                }
                $storeInfosHolder->refreshRemainAmounts = $storeDataHolder->refreshCount;

                $storeInfosHolder->storeInfoID = $storeHandler->UpdateStoreInfo($storeInfosHolder);
            }

            //response Client
            $resposeStore = new stdClass();

            $resposeStore->storeInfoID = $storeInfosHolder->storeInfoID;
            $resposeStore->uiStyle = $storeDataHolder->uIStyle;
            $resposeStore->refreshRemain = $storeInfosHolder->refreshRemainAmounts;
            $resposeStore->refreshMax = $storeDataHolder->refreshCount;
            $resposeStore->refreshCost = $storeDataHolder->refreshCost;
            $resposeStore->refreshCurrency = $storeDataHolder->refreshCostCurrency;

            $resposeStore->storetype = $storeDataHolder->storeType;
            $resposeStore->fixItems = $storeHandler->GetTrades($storeDataHolder->storeType, $currency, $storeInfosHolder->fixTradIDs);
            $resposeStore->randomItems = $storeHandler->GetTrades($storeDataHolder->storeType, $currency, $storeInfosHolder->randomTradIDs);

            $resposeStores[] = $resposeStore;
        }

        $userBagHandler = new UserBagHandler($userID);
        $result = new ResultData(ErrorCode::Success);
        $result->currencies = StoreUtility::GetCurrency($userBagHandler);
        $result->autoRefreshTime = $autoRefreshTime->remainSeconds;
        $result->stores = $resposeStores;

        return $result;
    }

}
