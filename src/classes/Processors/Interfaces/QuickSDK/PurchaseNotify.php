<?php

namespace Processors\Interfaces\QuickSDK;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\ResposeType;
use Games\Consts\StoreValue;
use Games\Exceptions\StoreException;
use Games\Store\StoreUtility;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\BaseProcessor;

/*
 * Description of QuickSDKNotify
 * 第三方支付通知完成購買
 */

class PurchaseNotify extends BaseProcessor {

    protected bool $mustSigned = false;
    protected int $resposeType = ResposeType::QuickSDKCallback;

    public function Process(): ResultData {


        $uid = InputHelper::post('uid');
        $cpOrderNo = InputHelper::post('cpOrderNo');
        $orderNo = InputHelper::post('orderNo');
        $payAmount = InputHelper::post('payAmount');
        $payCurrency = InputHelper::post('payCurrency');
        $usdAmount = InputHelper::post('usdAmount');
        $payStatus = InputHelper::post('payStatus');
        $extrasParams = InputHelper::post('extrasParams');
        $sign = InputHelper::post('sign');

        $accessor = new PDOAccessor(EnvVar::DBMain);
        $row = $accessor->FromTable('StorePurchaseOrders')->WhereEqual("OrderID", $cpOrderNo)->fetch();
        if (empty($row)) {
            throw new StoreException(StoreException::Error, ['[des]' => "no data"]);
        }
        $callbackKey = StoreUtility::GetCallbackkey($row->Device);
        $mysign = StoreUtility::GetMd5Sign($callbackKey);
        if ($mysign != $sign) { //簽名錯誤
            $result = new ResultData(ErrorCode::VerifyError);
            $result->message = "sign error";
            return $result;
        }

        $userID = $row->UserID;
        if ($payStatus == StoreValue::PaymentFailure) {
            //不用做任何事
            return new ResultData(ErrorCode::Success);
        }

        return new ResultData(ErrorCode::Success);
    }

}
