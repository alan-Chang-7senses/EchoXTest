<?php

namespace Processors\Interfaces\MyCard;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Games\Consts\ItemValue;
use Games\Consts\MailValues;
use Games\Consts\StoreValue;
use Games\Exceptions\StoreException;
use Games\Mails\MailsHandler;
use Games\Store\MyCardUtility;
use Games\Store\StoreHandler;
use Games\Users\ItemUtility;
use Games\Users\UserBagHandler;
use Generators\ConfigGenerator;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\BaseProcessor;

/*
 * Description of MyCardRestore
 * Mycard 補儲
 */

class Restore extends BaseProcessor {

    protected bool $mustSigned = false;

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

            $verifyResult = MyCardUtility::Verify($userID, $storePurchaseOrders->AuthCode);
            switch ($verifyResult->code) {
                case StoreValue::PurchaseVerifySuccess:
                    $storeHandler->UpdatePurchaseOrderStatus($orderID, StoreValue::PurchaseStatusFinish, $verifyResult->message);
                    break;
                case StoreValue::PurchaseVerifyRetry:
                    $storeHandler->UpdatePurchaseOrderStatus($orderID, StoreValue::PurchaseStatusProcessing, $verifyResult->message);
                    continue 2;
                case StoreValue::PurchaseVerifyMyCardError:
                    $storeHandler->UpdatePurchaseOrderStatus($orderID, StoreValue::PurchaseStatusMyCardError, $verifyResult->message);
                    continue 2;
                case StoreValue::PurchaseVerifyFailure:
                default :
                    $storeHandler->UpdatePurchaseOrderStatus($orderID, StoreValue::PurchaseStatusFailure, $verifyResult->message);
                    continue 2;
            }

            //加物品
            $userBagHandler = new UserBagHandler($userID);
            $additem = ItemUtility::GetBagItem($storePurchaseOrders->ItemID, $storePurchaseOrders->Amount);
            $userBagHandler->AddItems($additem, ItemValue::CauseStore);

            //加入通知信件
            $mailsHandler = new MailsHandler();
            $mailsHandler->AddMailArgument(MailValues::ArgumentTime, $storePurchaseOrders->CreateTime);
            $userMailID = $mailsHandler->AddMail($userID, ConfigGenerator::Instance()->MyCardRestoreMailID, ConfigGenerator::Instance()->MyCardRestoreMailDay,
                    MailValues::ReceiveStatusDone);
            $mailsHandler->AddMailItems($userMailID, $additem);
       
            $finishNum++;
        }

        $result = new ResultData(ErrorCode::Success);
        $result->FinishNum = $finishNum;
        return $result;
    }

}
