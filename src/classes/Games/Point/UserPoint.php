<?php

namespace Games\Point;

use Consts\Globals;
use Games\Accessors\AccessorFactory;
use Games\Consts\PointQueryValue;

class UserPoint
{
    private int $userID;
    private string $username;
    private string $symbol;

    public function __construct(int $userID, string $username, string $symbol)
    {
        $this->userID = $userID;
        $this->username = $username;
        $this->symbol = $symbol;
    }

    public function AddPoint(int|float $amount) : bool
    {
        $logAccessor = AccessorFactory::Log();
        $nowTime = $GLOBALS[Globals::TIME_BEGIN];
        $logAccessor->FromTable('PointOrder')->Add(
        [
            'Symbol' => $this->symbol,
            'UserID' => $this->userID,
            'Username' => $this->username,
            'Amount' => $amount,
            'CreateTime' => $nowTime,
            'UpdateTime' => $nowTime,
        ]);
        $serial = $logAccessor->LastInsertID();
        $orderID = $serial + PointQueryValue::OrderIDMin;
        $bind =['OrderID' => $orderID];
        $helper = new PointCurlHelper('post',PointQueryValue::URLAddPoint);
        $helper->AddBodyParams('symbol',$this->symbol);
        $helper->AddBodyParams('amount',$amount);
        $helper->AddBodyParams('refOrderId',$orderID);
        $helper->AddBodyParams('userId',$this->username);
        $curlReturn = $helper->SendAndGetResponse();

        if(empty($curlReturn))
        {
            $bind['OrderStatus'] = PointQueryValue::StatusReturnError;
            $logAccessor->WhereEqual('SerialNumber', $serial)->Modify($bind);
            self::AddIncompleteLog($serial,$this->userID);
            return false;
        }

        if($curlReturn->status->code !== PointQueryValue::CodeSuccess)
        {
            self::AddIncompleteLog($serial,$this->userID);
            $bind['OrderStatus'] = PointQueryValue::StatusReturnError;
            $bind['RespondCode'] = $curlReturn->status->code;
            $bind['Message'] = $curlReturn->status->message;
            $logAccessor->WhereEqual('SerialNumber', $serial)->Modify($bind);
            return false;
        }

        $bind['OrderStatus'] = $curlReturn->data->status;
        $bind['RespondCode'] = $curlReturn->status->code;
        $bind['Message'] = $curlReturn->status->message;
        $bind['RespondOrderID'] = $curlReturn->data->orderId;
        $bind['OrderType'] = $curlReturn->data->type;
        $bind['RespondAmount'] = $curlReturn->data->amount;
        $logAccessor->WhereEqual('SerialNumber', $serial)->Modify($bind);
        return true;
    }

    public function RefreshPoint(string $symbol)
    {

    }
    // public function RefreshPointBySerialNumbers(string $symbol,array $serialNumbers)
    // {

    // }

    private function ReAddPoint(int $orderID,int|float $amount) : bool
    {
        $helper = new PointCurlHelper('post',PointQueryValue::URLAddPoint);
        $helper->AddBodyParams('symbol',$this->symbol);
        $helper->AddBodyParams('amount',$amount);
        $helper->AddBodyParams('refOrderId',$orderID);
        $helper->AddBodyParams('userId',$this->username);
        if(empty($curlReturn))return false;
        return $curlReturn->status->code === PointQueryValue::CodeSuccess;
    }

    private function AddIncompleteLog(int $serial)
    {
        $logAccessor = AccessorFactory::Log();
        $logAccessor->ClearCondition()
        ->FromTable('PointOrderIncomplete')
        ->Add([
            'UserID' => $this->userID,
            'OrderSerialNumber' => $serial,
        ]);
    }   
}