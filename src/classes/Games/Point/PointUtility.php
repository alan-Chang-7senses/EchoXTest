<?php

namespace Games\Point;

use Consts\Globals;
use Games\Accessors\AccessorFactory;
use Games\Consts\PointQueryValue;

class PointUtility
{
    public static function GetUserPoint(string $username, string $symbol) : int|float|false
    {
        
        $helper = new PointCurlHelper('get',PointQueryValue::URLGetUserBalance);
        $helper->AddQueryStringParams('userId',$username);
        $helper->AddQueryStringParams('symbol',$symbol);
        $curlReturn = $helper->SendAndGetResponse();
        if(empty($curlReturn))return false;

        return $curlReturn->data->balance ?? false;
    }

    // public static function AddPoint(string $symbol, int $userID, string $userName,int|float $amount) : bool
    // {
    //     $logAccessor = AccessorFactory::Log();
    //     $nowTime = $GLOBALS[Globals::TIME_BEGIN];
    //     $logAccessor->FromTable('PointOrder')->Add(
    //     [
    //         'Symbol' => $symbol,
    //         'UserID' => $userID,
    //         'Username' => $userName,
    //         'Amount' => $amount,
    //         'CreateTime' => $nowTime,
    //         'UpdateTime' => $nowTime,
    //     ]);
    //     $serial = $logAccessor->LastInsertID();
    //     $orderID = $serial + PointQueryValue::OrderIDMin;
    //     $bind =['OrderID' => $orderID];
    //     $helper = new PointCurlHelper('post',PointQueryValue::URLAddPoint);
    //     $helper->AddBodyParams('symbol',$symbol);
    //     $helper->AddBodyParams('amount',$amount);
    //     $helper->AddBodyParams('refOrderId',$orderID);
    //     $helper->AddBodyParams('userId',$userName);
    //     $curlReturn = $helper->SendAndGetResponse();

    //     if(empty($curlReturn))
    //     {
    //         $bind['OrderStatus'] = PointQueryValue::StatusReturnError;
    //         $logAccessor->WhereEqual('SerialNumber', $serial)->Modify($bind);
    //         self::AddIncompleteLog($serial,$userID);
    //         return false;
    //     }

    //     if($curlReturn->status->code !== PointQueryValue::CodeSuccess)
    //     {
    //         self::AddIncompleteLog($serial,$userID);
    //         $bind['OrderStatus'] = PointQueryValue::StatusReturnError;
    //         $bind['RespondCode'] = $curlReturn->status->code;
    //         $bind['Message'] = $curlReturn->status->message;
    //         $logAccessor->WhereEqual('SerialNumber', $serial)->Modify($bind);
    //         return false;
    //     }

    //     $bind['OrderStatus'] = $curlReturn->data->status;
    //     $bind['RespondCode'] = $curlReturn->status->code;
    //     $bind['Message'] = $curlReturn->status->message;
    //     $bind['RespondOrderID'] = $curlReturn->data->orderId;
    //     $bind['OrderType'] = $curlReturn->data->type;
    //     $bind['RespondAmount'] = $curlReturn->data->amount;
    //     $logAccessor->WhereEqual('SerialNumber', $serial)->Modify($bind);
    //     return true;
    // }

    public static function RefreshUserPoint(string $symbol, int $userID)
    {
        $logAccessor = AccessorFactory::Log();

    }


}