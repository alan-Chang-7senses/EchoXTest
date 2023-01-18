<?php

namespace Processors\Store\Google;

use Consts\ErrorCode;
use Consts\Sessions;
use Games\Consts\StoreValue;
use Games\Exceptions\StoreException;
use Games\Store\GoogleUtility;
use Games\Store\Holders\GooglePurchaseData;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\Store\Purchase\BaseRefresh;
use Processors\Tools\Gene\Funcs\MailRepeatTxt;
use stdClass;

/*
 * Description of Google Cancel
 * Google 儲值設定收據並更新
 */

class SetReceipt extends BaseRefresh {

    protected int $nowPlat = StoreValue::PlatGoogle;

    public function PurchaseVerify(stdClass $purchaseOrders): stdClass {

        return GoogleUtility::Verify($purchaseOrders->ProductID, $purchaseOrders->AuthCode);
    }

    public function Process(): ResultData {
        
        return new ResultData(ErrorCode::Success);
        
        
        $this->userID = $_SESSION[Sessions::UserID];
        $this->orderID = InputHelper::post('orderID');
        $metadata = InputHelper::post('Metadata');
        $receipt = InputHelper::post('Receipt');
        
        //test
        MailRepeatTxt::Instance()->AddMessage("Metadata", $metadata);
        MailRepeatTxt::Instance()->AddMessage("Receipt", $receipt);
        //test
        
        $purchaseData = new GooglePurchaseData($metadata, $receipt);

        if ($purchaseData->purchaseState == 0) {
            $this->UpdateAuthCode($purchaseData->purchaseToken);
            $info = GoogleUtility::GetInfo($purchaseData->productId, $purchaseData->purchaseToken);
            if ($info->purchaseState == 0) {
                //test
                MailRepeatTxt::Instance()->AddMessage("Info", $metadata);
                //test
                return $this->HandleRefresh();
            } else {
                throw new StoreException(StoreException::PurchaseFailure);
            }
        } else {
            throw new StoreException(StoreException::PurchaseFailure);
        }
    }

}
