<?php

namespace Processors\Store\MyCard;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Games\Consts\StoreValue;
use Games\Exceptions\StoreException;
use Games\Pools\Store\StoreProductInfoPool;
use Games\Store\MyCardUtility;
use Holders\ResultData;
use Processors\Store\Purchase\BaseBuy;

/*
 * Description of MyCard buy
 * MyCard購物，細節見MyCard文件
 */

class Buy extends BaseBuy {

    protected int $nowPlat = StoreValue::PlatMyCard;

    public function Process(): ResultData {

        $orderID = $this->MakeOrder();

        $accessor = new PDOAccessor(EnvVar::DBMain);
        $rowInfo = $accessor->FromTable('StorePurchaseOrders')->WhereEqual("OrderID", $orderID)->WhereEqual("UserID", $this->userID)->fetch();

        if ($rowInfo == false) {
            throw new StoreException(StoreException::Error);
        }

        $productInfo = StoreProductInfoPool::Instance()->{$rowInfo->ProductID};
        if ($productInfo == false) {
            throw new StoreException(StoreException::Error);
        }

        $authCode = MyCardUtility::AuthGlobal($orderID, $this->userID, $productInfo);
        $accessor->Modify(["Receipt" => $authCode]);
        $result = new ResultData(ErrorCode::Success);
        $result->orderID = $orderID;
        $result->AuthCode = $authCode;
        return $result;
    }

}
