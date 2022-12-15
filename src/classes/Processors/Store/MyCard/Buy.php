<?php

namespace Processors\Store\MyCard;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\Globals;
use Games\Consts\StoreValue;
use Games\Exceptions\StoreException;
use Games\Pools\Store\StoreProductInfoPool;
use Games\Store\MyCardUtility;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\Store\Purchase\BaseBuy;

/*
 * Description of MyCard buy
 * MyCard購物，細節見MyCard文件
 */

class Buy extends BaseBuy {

    protected int $nowPlat = StoreValue::PlatMyCard;

    public function Process(): ResultData {

        $productName = InputHelper::post('productName'); //產品名稱
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

        if (!isset($productInfos, $rowInfo->ISOCurrency)) {
            throw new StoreException(StoreException::Error);
        }

        $productInfo = $productInfos->{$rowInfo->ISOCurrency};
        $authResult = MyCardUtility::AuthGlobal($orderID, $rowInfo->Device, $this->userID, $productInfo, $productName);
        $accessor->ClearAll()->FromTable("StorePurchaseOrders")->WhereEqual('OrderID', $orderID)->Modify([
            "Receipt" => $authResult->AuthCode,
            "MyCardTradeNo" => $authResult->MyCardTradeNo,
            "UpdateTime" => (int) $GLOBALS[Globals::TIME_BEGIN]
        ]);
        $result = new ResultData(ErrorCode::Success);
        $result->orderID = $orderID;

        if ($rowInfo->Device == StoreValue::DeviceiOS) {
            $result->AuthCode = "";
            $result->TransactionUrl = $authResult->TransactionUrl;
        } else {
            $result->AuthCode = $authResult->AuthCode;
            $result->TransactionUrl = "";
        }
        return $result;
    }

}
