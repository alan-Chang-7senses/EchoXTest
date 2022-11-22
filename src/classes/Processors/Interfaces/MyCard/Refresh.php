<?php

namespace Processors\Interfaces\MyCard;

use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\ResposeType;
use Games\Consts\StoreValue;
use Games\Exceptions\StoreException;
use Games\Store\MyCardUtility;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\Store\Purchase\BaseRefresh;
use stdClass;

/*
 * Description of MyCard Refresh
 * 由網頁更新目前購買格狀態，和貨幣資訊，MyCard請款
 */

class Refresh extends BaseRefresh {

    protected bool $mustSigned = false;
    protected int $nowPlat = StoreValue::PlatMyCard;
    protected int $resposeType = ResposeType::UniWebView;

    public function PurchaseVerify(stdClass $purchaseOrders): stdClass {

        return MyCardUtility::Verify($purchaseOrders->UserID, $purchaseOrders->Receipt);
    }

    public function Process(): ResultData {
        $params = new stdClass();
        $params->ReturnCode = InputHelper::Post('ReturnCode');
        $params->PayResult = InputHelper::Post('PayResult');
        $params->FacTradeSeq = InputHelper::Post('FacTradeSeq');
        $params->PaymentType = InputHelper::Post('PaymentType');
        $params->Amount = InputHelper::Post('Amount');
        $params->Currency = InputHelper::Post('Currency');
        $params->MyCardTradeNo = InputHelper::Post('MyCardTradeNo');
        $params->MyCardType = InputHelper::Post('MyCardType');
        $params->PromoCode = InputHelper::Post('PromoCode');
        $params->FacKey = getenv(EnvVar::MyCardFackey);
        $hash = InputHelper::Post('Hash');

        if (($params->ReturnCode != StoreValue::MyCardReturnSuccess) ||
                $params->PayResult != StoreValue::MyCardPaySuccess) {

            throw new StoreException(StoreException::PurchaseFailure, ['[cause]' => json_encode($params)]);
        }

        $myhash = MyCardUtility::Hash($params);
        if ($myhash !== $hash) {
            return new ResultData(ErrorCode::Unknown);
        }
        $this->orderID = (int) $params->FacTradeSeq;
        $this->userID = InputHelper::Get('userID');
        $resultRefresh = $this->HandleRefresh();

        $response = new stdClass();
        $response->currencies = $resultRefresh->currencies;
        $response->tradeID = $resultRefresh->tradeID;
        $response->remainInventory = $resultRefresh->remainInventory;
        $jsonstring = urlencode(json_encode($response));

        $result = new ResultData(ErrorCode::Success);
        $result->script = 'location.href = "uniwebview://PaySuccess?code=' . ErrorCode::Success . '&message=' . $jsonstring . '";';
        return $result;
    }

}
