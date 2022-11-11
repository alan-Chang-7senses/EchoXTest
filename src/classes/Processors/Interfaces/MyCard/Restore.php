<?php

namespace Processors\Interfaces\MyCard;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\Globals;
use Games\Consts\ItemValue;
use Games\Consts\StoreValue;
use Games\Exceptions\StoreException;
use Games\Pools\Store\StoreTradesPool;
use Games\Store\StoreHandler;
use Games\Store\StoreUtility;
use Games\Users\ItemUtility;
use Games\Users\UserBagHandler;
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

        $data = InputHelper::post('DATA');
        $response =  json_decode($data);
        if ($response->ReturnCode != StoreValue::MyCardReturnSuccess)
        {
            return new ResultData(ErrorCode::Unknown);
        }
        
//        $returnMsg = InputHelper::post('ReturnMsg');
//        $facServiceId = InputHelper::post('FacServiceId');
//        $totalNum = InputHelper::post('TotalNum');        
//        $facTradeSeq = InputHelper::post('FacTradeSeq');
        
                
        $accessor = new PDOAccessor(EnvVar::DBMain);
        for($i = 0; $i < $$response->TotalNum; $i++)
        {
            $orderID = $facTradeSeq[i];
            $row = $accessor->FromTable('StorePurchaseOrders')->
                            WhereEqual("OrderID", $orderID)->WhereEqual("UserID", $userID)->
                            WhereEqual("Plat", $this->nowPlat)->WhereEqual("Status", StoreValue::PurchaseStatusProcessing)->fetch();

            if (empty($row)) {
                throw new StoreException(StoreException::Error, ['[cause]' => "no data"]);
            }
            $storeHandler = new StoreHandler($userID);

            $storeHandler->UpdatePurchaseOrderStatus($orderID, StoreValue::PurchaseStatusVerify);

            $verifyResult = $this->PurchaseVerify($row);
            if ($verifyResult == StoreValue::PurchaseProcessRetry) {
                $storeHandler->UpdatePurchaseOrderStatus($orderID, StoreValue::PurchaseStatusProcessing);
                throw new StoreException(StoreException::PurchaseProcessing);
            } else if ($verifyResult == StoreValue::PurchaseProcessFailure) {
                $storeHandler->UpdatePurchaseOrderStatus($orderID, StoreValue::PurchaseStatusFailure);
                throw new StoreException(StoreException::PurchaseFailure);
            } else {
                $storeHandler->UpdatePurchaseOrderStatus($orderID, StoreValue::PurchaseStatusFinish);
            }

            $storeTradesHolder = StoreTradesPool::Instance()->{$row->TradeID};
            $userBagHandler = new UserBagHandler($userID);
            //加物品
            $additem = ItemUtility::GetBagItem($row->ItemID, $row->Amount);
            $userBagHandler->AddItems($additem, ItemValue::CauseStore);

            $result = new ResultData(ErrorCode::Success);
            $result->currencies = StoreUtility::GetCurrency($userBagHandler);
            $result->remainInventory = $storeTradesHolder->remainInventory;


        }
        
        $rows = $accessor->FromTable('StorePurchaseOrders')->
                WhereEqual("Status", StoreValue::PurchaseStatusProcessing)->WhereEqual("UserID", $userID)->WhereEqual("Plat", $this->nowPlat)->
                fetchAll();
        if (empty($rows)) {
            throw new StoreException(StoreException::PurchaseIsComplete);
        }

        $userBagHandler = new UserBagHandler($userID);

        foreach ($rows as $row) {

            $verifyResult = $this->PurchaseVerify($row);
            if ($verifyResult == StoreValue::PurchaseProcessSuccess) {

                $accessor->ClearCondition();
                $accessor->FromTable('StorePurchaseOrders')->WhereEqual("OrderID", $row->OrderID)->Modify([
                    "Status" => StoreValue::PurchaseStatusFinish,
                    "UpdateTime" => (int) $GLOBALS[Globals::TIME_BEGIN]]);

                //加物品
                $additem = ItemUtility::GetBagItem($row->ItemID, $row->Amount);
                $userBagHandler->AddItems($additem, ItemValue::CauseStore);
            }
        }

        $result = new ResultData(ErrorCode::Success);
        $result->currencies = StoreUtility::GetCurrency($userBagHandler);
        return $result;

        return StoreValue::PurchaseProcessRetry;
    }

}
