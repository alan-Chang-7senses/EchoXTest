<?php

namespace Processors\Store\Purchase;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\Globals;
use Consts\Sessions;
use Games\Consts\StoreValue;
use Games\Exceptions\StoreException;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\BaseProcessor;

/*
 * Description of BasePurchaseSetReceipt
 * 設定 MyCard AuthCode | Google Receipt
 * 
 */

abstract class BaseSetReceipt extends BaseProcessor {

    abstract function GetRecepit(): string;

    protected function SetReceipt(): ResultData {

        $orderID = InputHelper::post('orderID');
        $receipt = $this->GetRecepit();

        $userID = $_SESSION[Sessions::UserID];

        $accessor = new PDOAccessor(EnvVar::DBMain);
        $orderData = $accessor->FromTable('StorePurchaseOrders')->WhereEqual("OrderID", $orderID)->WhereEqual("UserID", $userID)->WhereEqual("Plat", StoreValue::PlatMyCard)->Fetch();
        if ($orderData == false) {
            throw new StoreException(StoreException::Error);
        }

        if (!empty($orderData->Receipt) && ($orderData->Receipt == $receipt)) {
            throw new StoreException(StoreException::Error);
        }

        $accessor->Modify([
            "Receipt" => $receipt,
            "UpdateTime" => (int) $GLOBALS[Globals::TIME_BEGIN]
        ]);

        return new ResultData(ErrorCode::Success);
    }

}
