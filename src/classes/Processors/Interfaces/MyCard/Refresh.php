<?php

namespace Processors\Interfaces\MyCard;

use Consts\ErrorCode;
use Consts\ResposeType;
use Consts\Sessions;
use Games\Consts\StoreValue;
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
    //protected int $resposeType = ResposeType::UniWebView;

    public function PurchaseVerify(stdClass $purchaseOrders): stdClass {

        return MyCardUtility::Verify($purchaseOrders->UserID, $purchaseOrders->Receipt);
    }

    public function Process(): ResultData {
        $datastring = InputHelper::Get('data');
        $paramData = json_decode(MyCardUtility::DecryptString($datastring));
        $this->orderID = $paramData->orderID;
        $this->userID = $paramData->userID;
        return $this->HandleRefresh();
    }

}
