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
        $storeHandler = new StoreHandler($userID);
        $autoRefreshTime = $storeHandler::GetRefreshTime();
        if (($autoRefreshTime == null) || ($autoRefreshTime->needRefresh)) {
            throw new StoreException(StoreException::Refreshed);
        }
        
        $accessor = new PDOAccessor(EnvVar::DBMain);
        $rowStoreInfo = $accessor->FromTable('StoreInfos')->WhereEqual('StoreInfoID', $storeInfoID)->WhereEqual('UserID', $userID)->Fetch();
        if ($rowStoreInfo == false) {
            throw new StoreException(StoreException::Error);
        }

        $storeInfosHolder = StoreUtility::GetStoreInfosHolder($rowStoreInfo);
        if ($storeInfosHolder->refreshRemainAmounts == StoreValue::RefreshRemainEmpty) {
            throw new StoreException(StoreException::NoRefreshCount);
        }

        $storeDataHolder = StoreDataPool::Instance()->{$storeInfosHolder->storeID};
        if ($storeDataHolder == false)//商店關閉
        {
            throw new StoreException(StoreException::ProductNotExist);            
        }
        
        $maxFixAmount = StoreUtility::GetMaxStoreAmounts($storeDataHolder->uIStyle);
        if ($maxFixAmount == StoreValue::UIUnset) {
            throw new StoreException(StoreException::Error, ['[cause]' => "table"]);
        }

        if ($storeDataHolder->refreshCostCurrency != StoreValue::CurrencyFree) {
            $itemID = StoreUtility::GetCurrencyItemID($storeDataHolder->refreshCostCurrency);
            $userBagHandler = new UserBagHandler($userID);
            if ($userBagHandler->DecItemByItemID($itemID, $storeDataHolder->refreshCost, ItemValue::CauseStore) == false) {
                throw new StoreException(StoreException::NotEnoughCurrency); //錢不夠
            }
        }

        $storeInfosHolder->refreshRemainAmounts--;

        if (StoreUtility::IsPurchaseStore($storeDataHolder->storeType)) {
            $storeInfosHolder->randomTradIDs = $storeHandler->UpdatePurchaseTrades($storeInfosHolder->storeID, $storeInfosHolder->randomTradIDs, $storeDataHolder->stochasticGroup, StoreValue::UIMaxFixItems - $maxFixAmount);
        } else if ($storeDataHolder->storeType == StoreValue::Counters) {
            $storeInfosHolder->randomTradIDs = $storeHandler->UpdateCountersTrades($storeInfosHolder->storeID, $storeInfosHolder->randomTradIDs, $storeDataHolder->stochasticGroup, StoreValue::UIMaxFixItems - $maxFixAmount);
        }
        $storeInfosHolder->storeInfoID = $storeHandler->UpdateStoreInfo($storeInfosHolder);

        //response
        $result = new ResultData(ErrorCode::Success);
        $result->refreshRemain = $storeInfosHolder->refreshRemainAmounts;
        $result->currencies = StoreUtility::GetCurrency($userBagHandler);
        $result->storetype = $storeDataHolder->storeType;
        $result->randomItems = $storeHandler->GetTrades($storeDataHolder->storeType, $storeInfosHolder->randomTradIDs);
        return $result;
    }

}
