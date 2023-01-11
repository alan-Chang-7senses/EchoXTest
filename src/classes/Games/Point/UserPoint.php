<?php

namespace Games\Point;

use Consts\Globals;
use Games\Accessors\AccessorFactory;
use Games\Consts\PointQueryValue;

class UserPoint
{
    private int $userID;
    private string $username;
    const InComplete = 0;
    const InProcess = 1;
    const Complete = 2;

    public function __construct(int $userID, string $username)
    {
        $this->userID = $userID;
        $this->username = $username;
    }

    public function ResetUserInfo(int $userID, string $username)
    {
        $this->userID = $userID;
        $this->username = $username;
    }

    public function GetPoint(string $symbol) : float|false
    {
        $this->RefreshPoint();
        $helper = new PointCurlHelper('get',PointQueryValue::URLGetUserBalance);
        $helper->AddQueryStringParams('userId',$this->username);
        $helper->AddQueryStringParams('symbol',$symbol);
        $curlReturn = $helper->SendAndGetResponse();
        if(empty($curlReturn))return false;
        if($curlReturn->status->code != PointQueryValue::CodeSuccess)return false;
        return $curlReturn->data->balance;
    }

    public function IsPointSync() : bool
    {
        $accessor = AccessorFactory::Log();
        $row = $accessor->SelectExpr('COUNT(*) as C')
                 ->FromTable('PointOrderIncomplete')
                 ->WhereEqual('UserID',$this->userID)
                 ->WhereCondition('ProcessStatus','!=',self::Complete)
                 ->Fetch();
        return $row->C == 0;                 
    }

    public function AddPoint(int|float $amount, string $symbol, int|null $specifyOrderID = null) : bool
    {
        $logAccessor = AccessorFactory::Log();
        $nowTime = $GLOBALS[Globals::TIME_BEGIN];
        $logAccessor->FromTable('PointOrder')->Add(
        [
            'Symbol' => $symbol,
            'UserID' => $this->userID,
            'Username' => $this->username,
            'Amount' => $amount,
            'LogTime' => $nowTime,
            'OrderType' => PointQueryValue::OrderTypeDeposit,
        ]);
        $serial = $logAccessor->LastInsertID();
        $orderID = $specifyOrderID ?? $serial + PointQueryValue::OrderIDMin;
        $bind =['OrderID' => $orderID];
        $helper = new PointCurlHelper('post',PointQueryValue::URLAddPoint);
        $helper->AddBodyParams('symbol',$symbol);
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

        if(empty($curlReturn->status->code) || $curlReturn->status->code !== PointQueryValue::CodeSuccess)
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
        $bind['RespondAmount'] = $curlReturn->data->amount;
        $logAccessor->WhereEqual('SerialNumber', $serial)->Modify($bind);
        return true;
    }

    public function DeductPoint(int|float $amount)
    {

    }

    private function RefreshPoint()
    {
        $accessor = AccessorFactory::Log();
        $userID = $this->userID;
        $incompleteRows = $accessor->Transaction(function() use ($accessor,$userID)
        {
            $rows = $accessor->FromTable('PointOrderIncomplete')
                     ->WhereEqual('UserID',$userID)
                     ->WhereEqual('ProcessStatus',self::InComplete)
                     ->ForUpdate()
                     ->FetchAll();
            if($rows !== false)
            {
                $serials = array_column($rows,'SerialNumber');
                $accessor->ClearCondition()
                         ->FromTable('PointOrderIncomplete')
                         ->WhereIn('SerialNumber',$serials)
                         ->Modify(['ProcessStatus' => self::InProcess]);
                return $rows;                         
            }
        });
        if($incompleteRows == false)return;

        $rows = $accessor->ClearCondition()
                         ->FromTable('PointOrder')
                         ->WhereIn('SerialNumber',array_column($incompleteRows,'OrderSerialNumber'))
                         ->FetchAll();
        if($rows === false)return;
        
        foreach($rows as $row)
        {
            $info = new RefreshInfo();
            $info->amount = $row->Amount;
            $info->orderType = $row->OrderType;
            $info->orderID = $row->OrderID;
            $info->symbol = $row->Symbol;
            $this->RefreshPointByRefreshInfo($info);
        }

        $incompleteSerials = array_column($incompleteRows,'SerialNumber');
        $accessor->ClearCondition()
                 ->FromTable('PointOrderIncomplete')
                 ->WhereIn('SerialNumber',$incompleteSerials)
                 ->Modify(['ProcessStatus' => self::Complete]);
    }
    //提供排程使用
    public function RefreshPointByRefreshInfo(RefreshInfo $info)
    {
        match($info->orderType)
        {
            PointQueryValue::OrderTypeDeposit
            => $this->AddPoint($info->amount,$info->symbol,$info->orderID),
        };
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