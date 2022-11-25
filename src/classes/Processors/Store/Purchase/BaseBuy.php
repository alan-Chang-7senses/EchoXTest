<?php

namespace Processors\Store\Purchase;

use Consts\Sessions;
use Games\Consts\StoreValue;
use Games\Exceptions\ItemException;
use Games\Exceptions\StoreException;
use Games\Pools\Store\StoreDataPool;
use Games\Pools\Store\StorePurchasePool;
use Games\Pools\Store\StoreTradesPool;
use Games\Store\StoreHandler;
use Games\Store\StoreUtility;
use Games\Users\ItemUtility;
use Games\Users\UserBagHandler;
use Helpers\InputHelper;
use Processors\BaseProcessor;

/*
 * Description of BaseBuy
 * 儲值購買 : 先確定可以購買，再建立訂單和第三方溝通
 */

abstract class BaseBuy extends BaseProcessor {

    protected int $userID;    
    protected int $tradeID;
    protected int $nowPlat = StoreValue::PlatNone;

    public function MakeOrder(): string {

        $this->tradeID = InputHelper::post('tradeID');

        $this->userID = $_SESSION[Sessions::UserID];
        $storeHandler = new StoreHandler($this->userID);

        $autoRefreshTime = $storeHandler->GetRefreshTime();
        if (($autoRefreshTime == null) || ($autoRefreshTime->needRefresh)) {
            throw new StoreException(StoreException::Refreshed);
        }

        $storeTradesHolder = StoreTradesPool::Instance()->{$this->tradeID};
        if (($this->userID != $storeTradesHolder->userID) || (StoreUtility::IsPurchaseStore($storeTradesHolder->storeType) == false)) {
            throw new StoreException(StoreException::Error);
        }

        $plat = StoreUtility::GetPurchasePlat($storeTradesHolder->storeType);
        if ($plat !== $this->nowPlat) {
            throw new StoreException(StoreException::Error);
        }

        if ($storeTradesHolder->remainInventory == StoreValue::InventoryDisplay) {
            throw new StoreException(StoreException::OutofStock);
        }

        $storeDataHolder = StoreDataPool::Instance()->{$storeTradesHolder->storeID};
        if ($storeDataHolder == false) {
            throw new StoreException(StoreException::ProductNotExist);
        }

        $userBagHandler = new UserBagHandler($this->userID);
        $storePurchaseHolder = StorePurchasePool::Instance()->{$storeTradesHolder->cPIndex};
        $additem = ItemUtility::GetBagItem($storePurchaseHolder->itemID, $storePurchaseHolder->amount);
        if ($userBagHandler->CheckAddStacklimit($additem) == false) {
            throw new ItemException(ItemException::UserItemStacklimitReached); //堆疊溢滿
        }

        //建立訂單
        return $storeHandler->CreatPurchaseOrder($storePurchaseHolder, $this->tradeID, $plat);
//        $result = new ResultData(ErrorCode::Success);
//        $result->orderID = $orderID;
//        return $result;
    }

}
