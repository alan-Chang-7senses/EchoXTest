<?php

namespace Processors\Store\Google;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\Globals;
use Consts\Sessions;
use Games\Consts\StoreValue;
use Games\Exceptions\StoreException;
use Games\Store\GoogleUtility;
use Games\Store\Holders\GooglePurchaseData;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\Store\Purchase\BaseRefresh;
use stdClass;

/*
 * Description of Google Cancel
 * Google 儲值設定收據並更新
 */

class SetReceipt extends BaseRefresh {

    protected int $nowPlat = StoreValue::PlatGoogle;
    private GooglePurchaseData $purchaseData;
    private string $receipt;

    public function PurchaseVerify(stdClass $purchaseOrders): stdClass {

        $result = GoogleUtility::Verify($this->purchaseData->productId, $this->purchaseData->purchaseToken);

        //add purchase log        
        if ($result->code == StoreValue::PurchaseVerifySuccess) {
            $logAccessor = new PDOAccessor(EnvVar::DBLog);
            $row = $logAccessor->FromTable('PlatPayment')->WhereEqual("OrderID", $this->orderID)->Fetch();
            if (empty($row)) {
                $logAccessor->ClearCondition()->Add([
                    "UserID" => $this->userID,
                    "PlatType" => $this->nowPlat,
                    "TransactionID" => $this->purchaseData->transactionID,
                    "PlatOrderID" => $this->purchaseData->orderId,
                    "OrderID" => $this->orderID,
                    "Amount" => $this->purchaseData->localizedPrice,
                    "Currency" => $this->purchaseData->isoCurrencyCode,
                    "TradeDateTime" => (int) $GLOBALS[Globals::TIME_BEGIN]
                ]);
            }
        }
        return $result;
    }

    function Process(): ResultData {

        $this->userID = $_SESSION[Sessions::UserID];
        $this->orderID = InputHelper::post('orderID');
        $metadata = InputHelper::post('Metadata');
        $this->receipt = InputHelper::post('Receipt');
        $this->purchaseData = new GooglePurchaseData($metadata, $this->receipt);

        if ($this->purchaseData->purchaseState == StoreValue::GooglePurchased) {
            $this->UpdateAuthCode($this->purchaseData->purchaseToken);
            $info = GoogleUtility::GetInfo($this->purchaseData->productId, $this->purchaseData->purchaseToken);

            if ($info->acknowledgementState == StoreValue::GoogleAcknowledgeConfirm) { //已購買
                throw new StoreException(StoreException::PurchaseIsComplete);
            } else {
                if ($info->purchaseState == StoreValue::GooglePurchased) {
                    return $this->HandleRefresh();
                } else {
                    throw new StoreException(StoreException::PurchaseFailure);
                }
            }
        } else {
            throw new StoreException(StoreException::PurchaseFailure);
        }
    }

}
