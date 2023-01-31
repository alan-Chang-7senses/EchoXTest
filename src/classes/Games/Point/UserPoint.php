<?php

namespace Games\Point;

use Consts\Globals;
use Games\Accessors\AccessorFactory;
use Games\Consts\PointQueryValue;
use Games\Pools\UserPool;

class UserPoint
{
    const InComplete = 0;
    const InProcess = 1;
    const Complete = 2;
    const PadAmount = 20;
    private int $userID;
    private string $username;

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

    public function GetPoint(string $symbol, bool $refreshIncomplete = true) : float|false
    {
        if($refreshIncomplete) $this->RefreshPoint();
        $helper = new PointCurlHelper('get',PointQueryValue::URLGetUserBalance);
        $helper->AddQueryStringParams('userId',$this->username);
        $helper->AddQueryStringParams('symbol',$symbol);
        $curlReturn = $helper->SendAndGetResponse();
        if(empty($curlReturn->status->code))return false;
        if($curlReturn->status->code != PointQueryValue::CodeSuccess)return false;
        return $curlReturn->data->balance;
    }

    public function IsPointSync() : bool
    {
        $accessor = AccessorFactory::Main();
        $row = $accessor->SelectExpr('COUNT(*) as C')
                 ->FromTable('PointOrderIncomplete')
                 ->WhereEqual('UserID',$this->userID)
                 ->WhereCondition('ProcessStatus','!=',self::Complete)
                 ->Fetch();
        return $row->C == 0;                 
    }

    public function AddPoint(int|float $amount, string $symbol) : bool
    {
        $accessor = AccessorFactory::Main();
        $nowTime = $GLOBALS[Globals::TIME_BEGIN];
        $bind = 
        [
            'Symbol' => $symbol,
            'UserID' => $this->userID,
            'Username' => $this->username,
            'Amount' => $amount,
            'LogTime' => $nowTime,
            'OrderType' => PointQueryValue::OrderTypes[PointQueryValue::OrderTypeDeposit],
        ];
        $accessor->FromTable('UserPointOrder')->Add($bind);
        $serial = $accessor->LastInsertID();
        $orderID = str_pad((string)$serial,self::PadAmount,'0',STR_PAD_LEFT);
        $bind['OrderID'] = $orderID;
        $helper = new PointCurlHelper('post',PointQueryValue::URLAddPoint);
        $helper->AddBodyParams('symbol',$symbol);
        $helper->AddBodyParams('amount',$amount);
        $helper->AddBodyParams('refOrderId',$orderID);
        $helper->AddBodyParams('userId',$this->username);
        $curlReturn = $helper->SendAndGetResponse();

        $this->LogOrder($bind,$curlReturn);
        if(empty($curlReturn->status))
        {
            $this->AddIncompleteOrder((int)$orderID);
            return false;
        } 
        if($curlReturn->status->code !== PointQueryValue::CodeSuccess)
        {
            $this->AddIncompleteOrder((int)$orderID);
            return false;
        }
        return true;
    }

    public function DeductPoint(int|float $amount)
    {

    }

    private function RefreshPoint()
    {
        $accessor = AccessorFactory::Main();
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
                $orderIDs = array_column($rows,'OrderID');
                $accessor->ClearCondition()
                         ->FromTable('PointOrderIncomplete')
                         ->WhereIn('OrderID',$orderIDs)
                         ->Modify(['ProcessStatus' => self::InProcess]);
                return $rows;  
            }
        });
        if($incompleteRows == false)return;

        $rows = $accessor->ClearCondition()
                         ->FromTable('UserPointOrder')
                         ->WhereIn('OrderID',array_column($incompleteRows,'OrderID'))
                         ->FetchAll();
        if($rows === false)return;
        
        $successDeposits = [];
        $failedDeposits = [];
        foreach($rows as $row)
        {
            $info = new RefreshInfo();
            $info->amount = $row->Amount;
            $info->orderType = $row->OrderType;
            $info->orderID = str_pad((string)$row->OrderID,self::PadAmount,'0',STR_PAD_LEFT);
            $info->symbol = $row->Symbol;
            $isDone = $this->RefreshPointByRefreshInfo($info);
            if($isDone) $successDeposits[] = $info->orderID;
            else $failedDeposits[] = $info->orderID;
        }
        self::FinishRefresh($successDeposits,$failedDeposits);
    }
    //提供排程使用
    public function RefreshPointByRefreshInfo(RefreshInfo $info) : bool
    {
        return match($info->orderType)
        {
            PointQueryValue::OrderTypes[PointQueryValue::OrderTypeDeposit]
            => $this->ReAddPoint((string)$info->orderID,$info->symbol,$info->amount),
            default => false,
        };
    }

    public static function FinishRefresh(array $successOrderIDs, array $failedOrderIDs)
    {
        $accessor = AccessorFactory::Main();
        if(!empty($successOrderIDs))
        {
            $accessor->ClearCondition()
                     ->FromTable('PointOrderIncomplete')
                     ->WhereIn('OrderID',$successOrderIDs)
                     ->Modify(['ProcessStatus' => self::Complete]);
        }
        if(!empty($failedOrderIDs))
        {
            $accessor->ClearCondition()
                     ->FromTable('PointOrderIncomplete')
                     ->WhereIn('OrderID',$failedOrderIDs)
                     ->Modify(['ProcessStatus' => self::InComplete]);
        }
    }

    private function ReAddPoint(string $orderID, string $symbol, float $amount) : bool
    {
        $helper = new PointCurlHelper('post',PointQueryValue::URLAddPoint);
        $helper->AddBodyParams('symbol',$symbol);
        $helper->AddBodyParams('amount',$amount);
        $helper->AddBodyParams('refOrderId',$orderID);
        $helper->AddBodyParams('userId',$this->username);
        $curlReturn = $helper->SendAndGetResponse();
        $bind = 
        [
            'OrderID' => $orderID,
            'Symbol' => $symbol,
            'UserID' => $this->userID,
            'Username' => $this->username,
            'Amount' => $amount,
            'LogTime' => $GLOBALS[Globals::TIME_BEGIN],
            'OrderType' => PointQueryValue::OrderTypes[PointQueryValue::OrderTypeDeposit],
        ];
        $this->LogOrder($bind,$curlReturn);
        if(empty($curlReturn->status->code))return false;
        return $curlReturn->status->code == PointQueryValue::CodeSuccess;
    }

    private function AddIncompleteOrder(int $orderID)
    {
        $accessor = AccessorFactory::Main();
        $accessor->ClearCondition()
            ->FromTable('PointOrderIncomplete')
            ->Add([
                    'UserID' => $this->userID,
                    'OrderID' => $orderID,
                 ]);
    }

    private function LogOrder(array $bind, $curlReturn)
    {
        if(empty($curlReturn->status))
        {
            $bind['OrderStatus'] = PointQueryValue::StatusReturnError;
            AccessorFactory::Log()->FromTable('PointOrder')->Add($bind);
            return;
        } 
        if($curlReturn->status->code !== PointQueryValue::CodeSuccess)
        {
            $bind['OrderStatus'] = PointQueryValue::StatusReturnError;
            $bind['RespondCode'] = $curlReturn->status->code;
            $bind['Message'] = $curlReturn->status->message;
            AccessorFactory::Log()->FromTable('PointOrder')->Add($bind);
            return;
        }
        
        $bind['OrderStatus'] = $curlReturn->data->status;
        $bind['RespondCode'] = $curlReturn->status->code;
        $bind['Message'] = $curlReturn->status->message;
        $bind['RespondOrderID'] = $curlReturn->data->orderId;
        $bind['RespondAmount'] = $curlReturn->data->amount;
        $bind['CallbackStatus'] = $curlReturn->data->callbackStatus;
        $bind['RedirectURL'] = $curlReturn->data->redirectUrl;
        AccessorFactory::Log()->FromTable('PointOrder')->Add($bind);
    }
    
}