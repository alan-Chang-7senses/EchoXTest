<?php

namespace Processors\Interfaces\MyCard;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Games\Consts\ItemValue;
use Games\Consts\StoreValue;
use Games\Exceptions\StoreException;
use Games\Store\MyCardUtility;
use Games\Store\StoreHandler;
use Games\Users\ItemUtility;
use Games\Users\UserBagHandler;
use Games\Users\UserUtility;
use Generators\ConfigGenerator;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\BaseProcessor;

/*
 * Description of MyCardRestore
 * Mycard 補儲
 */

class Restore extends BaseProcessor {

    protected bool $mustSigned = true;

    public function Process(): ResultData {

        if (MyCardUtility::CheckAllowIP() == false) {
            throw new StoreException(StoreException::Error, ['[cause]' => "RL34"]);
        }

        $data = InputHelper::post('DATA');
        $response = json_decode($data);
        if ($response->ReturnCode != StoreValue::MyCardReturnSuccess) {
            return new ResultData(ErrorCode::Unknown);
        }

        $accessor = new PDOAccessor(EnvVar::DBMain);
        $finishNum = 0;
        for ($i = 0; $i < $response->TotalNum; $i++) {
            $orderID = $response->FacTradeSeq[$i];
            $storePurchaseOrders = $accessor->ClearCondition()->FromTable('StorePurchaseOrders')->
                            WhereEqual("OrderID", $orderID)->WhereEqual("Status", StoreValue::PurchaseStatusProcessing)->fetch();

            if (empty($storePurchaseOrders)) {
                continue;
            }

            $userID = $storePurchaseOrders->UserID;

            $storeHandler = new StoreHandler($userID);
            $storeHandler->UpdatePurchaseOrderStatus($orderID, StoreValue::PurchaseStatusVerify, "Verifying");

            $verifyResult = MyCardUtility::Verify($userID, $storePurchaseOrders->Receipt);
            if ($verifyResult->code == StoreValue::PurchaseProcessRetry) {
                $storeHandler->UpdatePurchaseOrderStatus($orderID, StoreValue::PurchaseStatusProcessing, $verifyResult->message);
                continue;
            } else if ($verifyResult->code == StoreValue::PurchaseProcessFailure) {
                $storeHandler->UpdatePurchaseOrderStatus($orderID, StoreValue::PurchaseStatusFailure, $verifyResult->message);
                continue;
            } else {
                $storeHandler->UpdatePurchaseOrderStatus($orderID, StoreValue::PurchaseStatusFinish, $verifyResult->message);
            }

            //加物品
            $userBagHandler = new UserBagHandler($userID);
            $additem = ItemUtility::GetBagItem($storePurchaseOrders->ItemID, $storePurchaseOrders->Amount);
            $userBagHandler->AddItems($additem, ItemValue::CauseStore);
            
            //加入通知信件
            UserUtility::AddMailItemsWithReceive($userID, [$additem], ConfigGenerator::Instance()->MyCardRestoreMailID, ConfigGenerator::Instance()->MyCardRestoreMailDay);
            $finishNum++;
        }

        $result = new ResultData(ErrorCode::Success);
        $result->FinishNum = $finishNum;
        return $result;
    }

}
