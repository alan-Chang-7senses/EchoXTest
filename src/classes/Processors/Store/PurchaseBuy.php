<?php

namespace Processors\Store;

use Consts\ErrorCode;
use Consts\Sessions;
use Games\Consts\StoreValue;
use Games\Exceptions\ItemException;
use Games\Exceptions\StoreException;
use Games\Pools\Store\StorePurchasePool;
use Games\Pools\Store\StoreTradesPool;
use Games\Store\StoreHandler;
use Games\Store\StoreUtility;
use Games\Users\ItemUtility;
use Games\Users\UserBagHandler;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\BaseProcessor;

/*
 * Description of PurchaseBuy
 * 儲值購買 : 先確定可以購買，再建立訂單和第三方溝通
 */

class PurchaseBuy extends BaseProcessor {

    public function Process(): ResultData {

        $tradeID = InputHelper::post('tradeID');
        $device = InputHelper::post('device');

        $userID = $_SESSION[Sessions::UserID];
        $storeHandler = new StoreHandler($userID);

        $storeTradesHolder = StoreTradesPool::Instance()->{$tradeID};
        if (($userID != $storeTradesHolder->userID) || ($storeTradesHolder->storeType != StoreValue::Purchase)) {
            throw new StoreException(StoreException::Error, ['[des]' => "params"]);
        }
        
        if (($device != StoreValue::Andriod) && ($device != StoreValue::iOS))
        {
            throw new StoreException(StoreException::Error, ['[des]' => "device"]);            
        }

        if ($storeTradesHolder->remainInventory == StoreValue::InventoryDisplay) {
            throw new StoreException(StoreException::OutofStock);
        }

        $autoRefreshTime = StoreUtility::CheckAutoRefreshTime($storeTradesHolder->updateTime);
        if ($autoRefreshTime->needRefresh) {
            throw new StoreException(StoreException::Refreshed);
        }

        $userBagHandler = new UserBagHandler($userID);
        $storePurchaseHolder = StorePurchasePool::Instance()->{$storeTradesHolder->cPIndex};
        $additem = ItemUtility::GetBagItem($storePurchaseHolder->itemID, $storePurchaseHolder->amount);
        if ($userBagHandler->CheckAddStacklimit($additem) == false) {
            throw new ItemException(ItemException::UserItemStacklimitReached); //堆疊溢滿
        }

        //建立訂單
        $orderID = $storeHandler->CreatPurchaseOrder($storePurchaseHolder);
        $result = new ResultData(ErrorCode::Success);
        $result->orderID = $orderID;
        return $result;
    }

}
