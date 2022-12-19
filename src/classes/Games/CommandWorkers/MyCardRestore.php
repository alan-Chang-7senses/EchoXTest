<?php

namespace Games\CommandWorkers;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Games\Consts\ItemValue;
use Games\Consts\MailValues;
use Games\Consts\StoreValue;
use Games\Mails\MailsHandler;
use Games\Store\MyCardUtility;
use Games\Store\StoreHandler;
use Games\Users\ItemUtility;
use Games\Users\UserBagHandler;
use Generators\ConfigGenerator;

/**
 * Description of MycardRestore
 *
 */
class MyCardRestore extends BaseWorker {

    public function Process(): array {

        $queryResult = MyCardUtility::SDKTradeQuery();
        $accessor = new PDOAccessor(EnvVar::DBMain);
        $finishNum = 0;
        $listInfos = [];
        foreach ($queryResult->ListSDKTradeQuery as $tradeQuery) {
            $orderID = $tradeQuery->FacTradeSeq;
            
            $listInfos[] = $orderID ;
            $storePurchaseOrders = $accessor->ClearCondition()->FromTable('StorePurchaseOrders')->
                            WhereEqual("OrderID", $orderID)->WhereEqual("Status", StoreValue::PurchaseStatusProcessing)->fetch();

            if (empty($storePurchaseOrders)) {
                continue;
            }

            $userID = $storePurchaseOrders->UserID;

            $storeHandler = new StoreHandler($userID);
            $storeHandler->UpdatePurchaseOrderStatus($orderID, StoreValue::PurchaseStatusVerify, "Verifying");
            $verifyResult = MyCardUtility::SDKVerify($userID, $storePurchaseOrders->AuthCode, $tradeQuery);

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
            break;
        }

        return $listInfos;
    }

}
