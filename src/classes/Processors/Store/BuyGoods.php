<?php

namespace Processors\Store;

use Consts\ErrorCode;
use Consts\Sessions;
use Games\Consts\ItemValue;
use Games\Consts\StoreValue;
use Games\Exceptions\StoreException;
use Games\Pools\Store\StoreCountersPool;
use Games\Pools\Store\StoreTradesPool;
use Games\Store\StoreHandler;
use Games\Store\StoreUtility;
use Games\Users\UserBagHandler;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\BaseProcessor;

/*
 * Description of BuyGoods
 */

class BuyGoods extends BaseProcessor {

    public function Process(): ResultData {

        $tradeID = InputHelper::post('tradeID');

        $userID = $_SESSION[Sessions::UserID];
        $storeHandler = new StoreHandler($userID);

        $storeTradesHolder = StoreTradesPool::Instance()->{$tradeID};
        if ($storeTradesHolder->storeType != StoreValue::Counters) {
            throw new StoreException(StoreException::Error);
        }

        if ($storeTradesHolder->remainInventory == StoreValue:: InventoryDisplay) {
            throw new StoreException(StoreException::OutofStock);
        }

        $autoRefreshTime = StoreUtility::CheckAutoRefreshTime($storeTradesHolder->updateTime);
        if ($autoRefreshTime->needRefresh) {
            throw new StoreException(StoreException::Refreshed);
        }

        $userBagHandler = new UserBagHandler($userID);
        $storeCountersHolder = StoreCountersPool::Instance()->{$storeTradesHolder->cPIndex};
        if ($userBagHandler->CheckItemStack($storeCountersHolder->itemID, $storeCountersHolder->amount)) {
            //堆疊溢滿
            throw new StoreException(StoreException::Refreshed);
        }

        $storeTradesHolder->remainInventory--;
        //扣錢
        
        $storeHandler->UpdateStoreTrades($storeTradesHolder);
        $userBagHandler->AddItem($storeCountersHolder->itemID, $storeCountersHolder->amount, ItemValue:: CauseStoreBy);
        
        return new ResultData(ErrorCode::Success);
    }

}
