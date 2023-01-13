<?php

namespace Processors\Store\Google;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Games\Consts\StoreValue;
use Games\Exceptions\StoreException;
use Games\Pools\Store\StoreProductInfoPool;
use Holders\ResultData;
use Processors\Store\Purchase\BaseBuy;

/*
 * Description of Google/Buy
 * Google 購物
 */

class Buy extends BaseBuy {

    protected int $nowPlat = StoreValue::PlatGoogle;

    public function Process(): ResultData {

        $orderID = $this->MakeOrder();

        $accessor = new PDOAccessor(EnvVar::DBMain); 
        $rowInfo = $accessor->executeBindFetch(Sprintf("SELECT *,Device, ISOCurrency FROM StorePurchaseOrders inner JOIN  StoreUserInfos  USING(`UserID`) WHERE ORDERID = '%s';", $orderID), []);
        if ($rowInfo == false) {
            throw new StoreException(StoreException::Error);
        }

        $productInfos = StoreProductInfoPool::Instance()->{$rowInfo->ProductID};
        if ($productInfos == false) {
            throw new StoreException(StoreException::Error);
        }
       
        $result = new ResultData(ErrorCode::Success);
        $result->orderID = $orderID;
        return $result;
    }

}
