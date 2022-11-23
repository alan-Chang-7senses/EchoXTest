<?php

namespace Processors\Store\Purchase;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\Globals;
use Consts\Sessions;
use Games\Consts\ItemValue;
use Games\Consts\StoreValue;
use Games\Exceptions\StoreException;
use Games\Store\StoreUtility;
use Games\Users\ItemUtility;
use Games\Users\UserBagHandler;
use Holders\ResultData;
use Processors\BaseProcessor;
use stdClass;

/*
 * Description of MyCardRestore
 * Mycard 補儲
 */

abstract class BaseRestore extends BaseProcessor {

    protected int $nowPlat = StoreValue::PlatNone;

    abstract function PurchaseVerify(stdClass $purchaseOrders): int;

    public function Process(): ResultData {

        $userID = $_SESSION[Sessions::UserID];

        $accessor = new PDOAccessor(EnvVar::DBMain);
        $rows = $accessor->FromTable('StorePurchaseOrders')->
                WhereEqual("Status", StoreValue::PurchaseStatusProcessing)->WhereEqual("UserID", $userID)->WhereEqual("Plat", $this->nowPlat)->
                fetchAll();
        if (empty($rows)) {
            throw new StoreException(StoreException::PurchaseIsComplete);
        }

        $userBagHandler = new UserBagHandler($userID);

        foreach ($rows as $row) {

            $verifyResult = $this->PurchaseVerify($row);
            if ($verifyResult == StoreValue::PurchaseVerifySuccess) {

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
    }

}
