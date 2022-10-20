<?php

namespace Processors\Store;

use Consts\ErrorCode;
use Consts\Sessions;
use Games\Consts\StoreValue;
use Games\Exceptions\StoreException;
use Games\Pools\Store\StoreTradesPool;
use Games\Store\StoreUtility;
use Games\Users\UserBagHandler;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\BaseProcessor;

/*
 * Description of PurchaseRefresh
 * 更新目前購買格狀態，和貨幣資訊
 */

class PurchaseRefresh extends BaseProcessor {

    public function Process(): ResultData {

        $tradeID = InputHelper::post('tradeID');

        $userID = $_SESSION[Sessions::UserID];
        $storeTradesHolder = StoreTradesPool::Instance()->{$tradeID};
        if (($userID != $storeTradesHolder->userID) || ($storeTradesHolder->storeType != StoreValue::Purchase)) {
            throw new StoreException(StoreException::Error, ['[des]' => "params"]);
        }

        $userBagHandler = new UserBagHandler($userID);
        $responseCurrencies = [];
        foreach (StoreValue::Currencies as $currency) {
            $itemID = StoreUtility::GetCurrencyItemID($currency);
            $responseCurrencies[] = $userBagHandler->GetItemAmount($itemID);
        }

        $result = new ResultData(ErrorCode::Success);
        $result->currencies = $responseCurrencies;
        $result->remainInventory = $storeTradesHolder->remainInventory;
        return $result;
    }

}
