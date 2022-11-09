<?php

namespace Processors\Store;

use Consts\ErrorCode;
use Consts\Sessions;
use Games\Consts\ItemValue;
use Games\Consts\StoreValue;
use Games\Exceptions\ItemException;
use Games\Exceptions\StoreException;
use Games\Pools\Store\StoreCountersPool;
use Games\Pools\Store\StoreDataPool;
use Games\Pools\Store\StoreTradesPool;
use Games\Store\StoreHandler;
use Games\Store\StoreUtility;
use Games\Users\ItemUtility;
use Games\Users\UserBagHandler;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\BaseProcessor;

/*
 * Description of BuyGoods
 * 購買一般商店物品
 */

class BuyGoods extends BaseProcessor {

    public function Process(): ResultData {

        $tradeID = InputHelper::post('tradeID');

        $userID = $_SESSION[Sessions::UserID];
        $storeHandler = new StoreHandler($userID);
        $autoRefreshTime = $storeHandler->GetRefreshTime();
        if (($autoRefreshTime == null) || ($autoRefreshTime->needRefresh)) {
            throw new StoreException(StoreException::Refreshed);
        }

        $storeTradesHolder = StoreTradesPool::Instance()->{$tradeID};
        if (($userID != $storeTradesHolder->userID) || ($storeTradesHolder->storeType != StoreValue::TypeCounters)) {
            throw new StoreException(StoreException::Error, ['[cause]' => "params"]);
        }

        $storeDataHolder = StoreDataPool::Instance()->{$storeTradesHolder->storeID};
        if ($storeDataHolder == false) {//商店關閉
            throw new StoreException(StoreException::ProductNotExist);
        }

        if ($storeTradesHolder->remainInventory == StoreValue:: InventoryDisplay) {
            throw new StoreException(StoreException::OutofStock);
        }

        $userBagHandler = new UserBagHandler($userID);
        $storeCountersHolder = StoreCountersPool::Instance()->{$storeTradesHolder->cPIndex};
        $additem = ItemUtility::GetBagItem($storeCountersHolder->itemID, $storeCountersHolder->amount);
        if ($userBagHandler->CheckAddStacklimit($additem) == false) {
            throw new ItemException(ItemException::UserItemStacklimitReached); //堆疊溢滿
        }

        //扣錢
        if ($storeCountersHolder->currency != StoreValue::CurrencyFree) {
            $itemID = StoreUtility::GetCurrencyItemID($storeCountersHolder->currency);
            if ($userBagHandler->DecItemByItemID($itemID, $storeCountersHolder->price, ItemValue::CauseStore) == false) {
                throw new StoreException(StoreException::NotEnoughCurrency); //錢不夠
            }
        }
        if ($storeTradesHolder->remainInventory != StoreValue::InventoryNoLimit) {
            $storeTradesHolder->remainInventory--;
            $storeHandler->UpdateStoreTradesRemain($storeTradesHolder);
        }

        //加物品
        $userBagHandler->AddItems($additem, ItemValue::CauseStore);
        $result = new ResultData(ErrorCode::Success);
        $result->currencies = StoreUtility::GetCurrency($userBagHandler);
        $result->remainInventory = $storeTradesHolder->remainInventory;
        return $result;
    }

}
