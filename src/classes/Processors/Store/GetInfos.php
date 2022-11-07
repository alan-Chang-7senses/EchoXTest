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
        $device = InputHelper::get('device');

        $storeHandler = new StoreHandler($userID);

        $accessor = new PDOAccessor(EnvVar::DBMain);
        $rowStoreUserInfos = $accessor->FromTable('StoreUserInfos')->WhereEqual('UserID', $userID)->Fetch();
        if ($rowStoreUserInfos == false) {
            $autoRefreshTime = StoreUtility::CheckAutoRefreshTime((int) $GLOBALS[Globals::TIME_BEGIN]);
            $autoRefreshTime->needRefresh = true;
        } else {
            $autoRefreshTime = StoreUtility::CheckAutoRefreshTime($rowStoreUserInfos->AutoRefreshTime);
        }
        $storeHandler->UpdateUserStoreInfos($device, $autoRefreshTime, $rowStoreUserInfos);

        //get store
        $storeIDs = $storeHandler->GetStoreDatas();
        $resposeStores = [];
        foreach ($storeIDs as $storeID) {
            $storeDataHolder = StoreDataPool::Instance()->{$storeID->StoreID};
            if ($storeDataHolder == false) {
                continue;
            }
            
            if (($storeDataHolder->storeType == StoreValue::MyCard) &&
                    (getenv(EnvVar::MycardSwitch) != 'true')) {
                continue;
            }

            //互斥的平台
            if (($device == StoreValue::iOS && $storeDataHolder->storeType == StoreValue::GoogleIAB) ||
                    ($device == StoreValue::Android && $storeDataHolder->storeType == StoreValue::AppleIAP)) {
                continue;
            }

            $maxFixAmount = StoreUtility::GetMaxStoreAmounts($storeDataHolder->uIStyle);
            if ($maxFixAmount == StoreValue::UIUnset) {
                continue;
            }

            $accessor->ClearCondition();
            $rowStoreInfo = $accessor->FromTable('StoreInfos')->WhereEqual('UserID', $userID)->WhereEqual('StoreID', $storeID->StoreID)->Fetch();
            $storeInfosHolder = StoreUtility::GetStoreInfosHolder($rowStoreInfo);
            $storeInfosHolder->storeID = $storeID->StoreID;

            $isNeedAutoRefresh = true;
            if (!empty($rowStoreInfo)) {
                if ($autoRefreshTime->needRefresh == false) {
                    $isNeedAutoRefresh = false;
                }
            }


            if ($isNeedAutoRefresh) {

                if (StoreUtility::IsPurchaseStore($storeDataHolder->storeType)) {
                    $storeInfosHolder->fixTradIDs = $storeHandler->UpdatePurchaseTrades($storeDataHolder->storeType, $storeInfosHolder->fixTradIDs, $storeDataHolder->fixedGroup, $maxFixAmount);
                    $storeInfosHolder->randomTradIDs = $storeHandler->UpdatePurchaseTrades($storeDataHolder->storeType, $storeInfosHolder->randomTradIDs, $storeDataHolder->stochasticGroup, StoreValue::UIMaxFixItems - $maxFixAmount);
                } else if ($storeDataHolder->storeType == StoreValue::Counters) {
                    $storeInfosHolder->fixTradIDs = $storeHandler->UpdateCountersTrades($storeInfosHolder->fixTradIDs, $storeDataHolder->fixedGroup, $maxFixAmount);
                    $storeInfosHolder->randomTradIDs = $storeHandler->UpdateCountersTrades($storeInfosHolder->randomTradIDs, $storeDataHolder->stochasticGroup, StoreValue::UIMaxFixItems - $maxFixAmount);
                } else {
                    continue;
                }
                $storeInfosHolder->refreshRemainAmounts = $storeDataHolder->refreshCount;

                $storeInfosHolder->storeInfoID = $storeHandler->UpdateStoreInfo($storeInfosHolder);
            }

            //response
            $resposeStore = new stdClass();

            $resposeStore->storeInfoID = $storeInfosHolder->storeInfoID;
            $resposeStore->uiStyle = $storeDataHolder->uIStyle;
            $resposeStore->refreshRemain = $storeInfosHolder->refreshRemainAmounts;
            $resposeStore->refreshMax = $storeDataHolder->refreshCount;
            $resposeStore->refreshCost = $storeDataHolder->refreshCost;
            $resposeStore->refreshCurrency = $storeDataHolder->refreshCostCurrency;

            $resposeStore->storetype = $storeDataHolder->storeType;
            $resposeStore->fixItems = $storeHandler->GetTrades($storeDataHolder->storeType, $storeInfosHolder->fixTradIDs);
            $resposeStore->randomItems = $storeHandler->GetTrades($storeDataHolder->storeType, $storeInfosHolder->randomTradIDs);

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
