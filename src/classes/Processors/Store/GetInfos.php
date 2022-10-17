<?php

namespace Processors\Store;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\Sessions;
use Games\Consts\StoreValue;
use Games\Pools\Store\StoreDataPool;
use Games\Store\Holders\StoreInfosHolder;
use Games\Store\StoreHandler;
use Games\Store\StoreUtility;
use Holders\ResultData;
use Processors\BaseProcessor;
use stdClass;

/*
 * Description of GetInfos
 */

class GetInfos extends BaseProcessor {

    public function Process(): ResultData {

        $userID = $_SESSION[Sessions::UserID];
        $storHandler = new StoreHandler($userID);
        $storeDatas = $storHandler->GetStoreDatas();

        $accessor = new PDOAccessor(EnvVar::DBMain);
        $resposeStores = [];
        foreach ($storeDatas as $storeData) {
            $storeData = StoreDataPool::Instance()->{$storeData->StoreID};
            if ($storeData == false) {
                continue;
            }
            $maxFixAmount = StoreUtility::GetMaxStoreAmounts($storeData->uIStyle);
            if ($maxFixAmount == StoreValue::UINoItems) {
                continue;
            }

            $storeDataHolder = new StoreInfosHolder();
            $storeDataHolder->storeID = $storeData->storeID;
            $accessor->ClearCondition();
            $rowStoreInfo = $accessor->FromTable('StoreInfos')->WhereEqual('UserID', $userID)->WhereEqual('StoreID', $storeData->storeID)->Fetch();

            $fixIDs = null;
            $randomIDs = null;

            $needRefresh = true;
            if (empty($rowStoreInfo)) {
                $storeDataHolder->storeInfoID = StoreValue::NoStoreInfoID; //沒有資料,產出新的
                $storeDataHolder->remainRefreshTimes = $storeData->refreshCount;
            } else {
                //$row->UpdateTIme;
                $storeDataHolder->storeInfoID = $rowStoreInfo->StoreInfoID;
                if ($storHandler->CheckRefresh($rowStoreInfo->UpdateTime)) {// if need refresh 
                    $fixIDs = $rowStoreInfo->FixTradIDs;
                    $randomIDs = $rowStoreInfo->RandomTradIDs;
                    $storeDataHolder->remainRefreshTimes = $storeData->refreshCount;
                } else {
                    $needRefresh = false;
                    $storeDataHolder->fixTradIDs = $rowStoreInfo->FixTradIDs;
                    $storeDataHolder->randomTradIDs = $rowStoreInfo->RandomTradIDs;
                    $storeDataHolder->remainRefreshTimes = $rowStoreInfo->RemainRefreshTimes;
                }
            }

            if ($needRefresh) {

                if ($storeData->storeType == StoreValue::Purchase) {
                    $storeDataHolder->fixTradIDs = $storHandler->UpdatePurchaseTrades($fixIDs, $storeData->fixedGroup, $maxFixAmount);
                    $storeDataHolder->randomTradIDs = $storHandler->UpdatePurchaseTrades($randomIDs, $storeData->stochasticGroup, StoreValue::UIMaxFixItems - $maxFixAmount);
                } else if ($storeData->storeType == StoreValue::Counters) {
                    $storeDataHolder->fixTradIDs = $storHandler->UpdateCountersTrades($fixIDs, $storeData->fixedGroup, $maxFixAmount);
                    $storeDataHolder->randomTradIDs = $storHandler->UpdateCountersTrades($randomIDs, $storeData->stochasticGroup, StoreValue::UIMaxFixItems - $maxFixAmount);
                } else {
                    continue;
                }
                $storeDataHolder->storeInfoID = $storHandler->UpdateStoreInfo($storeDataHolder);
            }


            //add response

            $fixPurchase = [];
            $randomPurchase = [];
            $fixCounters = [];
            $randomCounters = [];

            $resposeStore = new stdClass();
            $resposeStore->storeInfoID = $storeDataHolder->storeInfoID;
            $resposeStore->fix = $fixPurchase;
            $resposeStores[] = $resposeStores;
        }


        /*
          if (empty($rows)) {
          $this->AddNewStoreItems();
          } else {
          $this->ResetStoreItems($rows);
          }
         */
        //return $result;


        return new ResultData(ErrorCode::Success);
    }

}
