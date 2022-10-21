<?php

namespace Processors\Interfaces\QuickSDK;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\ResposeType;
use Games\Consts\ItemValue;
use Games\Consts\StoreValue;
use Games\Exceptions\StoreException;
use Games\Store\StoreHandler;
use Games\Store\StoreUtility;
use Games\Users\ItemUtility;
use Games\Users\UserBagHandler;
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

        //$uid = InputHelper::post('uid');
        //必要的值
        $orderID = InputHelper::post('cpOrderNo');
        $orderNo = InputHelper::post('orderNo');
        $payAmount = InputHelper::post('payAmount');
        $payCurrency = InputHelper::post('payCurrency');
        $usdAmount = InputHelper::post('usdAmount');
        $payStatus = InputHelper::post('payStatus');
        //$extrasParams = InputHelper::post('extrasParams');
        $sign = InputHelper::post('sign');

        $accessor = new PDOAccessor(EnvVar::DBMain);
        $row = $accessor->FromTable('StorePurchaseOrders')->WhereEqual("OrderID", $orderID)->fetch();
        if (empty($row)) {
            throw new StoreException(StoreException::Error, ['[des]' => "no data"]);
        }

        $callbackKey = StoreUtility::GetCallbackkey($row->Device);
        $mysign = StoreUtility::GetMd5Sign($callbackKey);
        if ($mysign != $sign) { //簽名錯誤
            return new ResultData(ErrorCode::VerifyError, "sign error");
        }

        if (($row->Status != StoreValue::PurchaseStatusProcessing) && ($row->Status != StoreValue::PurchaseQuickSDKFailure)) {
            return new ResultData(ErrorCode::VerifyError, "status error");
        }

        $userID = $row->UserID;
        $storeHandler = new StoreHandler($userID);

        if ($payStatus == StoreValue::PaymentFailure) {
            //不用做任何事, 變更狀態以便知道訂單失敗原因
            if ($row->Status != StoreValue::PurchaseQuickSDKFailure) {
                $storeHandler->UpdatePurchaseOrderStatus($orderID, StoreValue::PurchaseQuickSDKFailure);
            }
            return new ResultData(ErrorCode::Success);
        }

        $userBagHandler = new UserBagHandler($userID);
        //加物品
        $additem = ItemUtility::GetBagItem($row->ItemID, $row->Amount);
        $userBagHandler->AddItems($additem, ItemValue::CauseStore);
        
        //更新訂單
        $storeHandler->FinishPurchaseOrder($orderID, $orderNo, $usdAmount, $payAmount, $payCurrency);
        return new ResultData(ErrorCode::Success);
    }

}
