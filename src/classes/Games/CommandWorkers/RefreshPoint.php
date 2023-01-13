<?php

namespace Games\CommandWorkers;

use Games\Accessors\AccessorFactory;
use Games\Consts\PointQueryValue;
use Games\Point\RefreshInfo;
use Games\Point\UserPoint;
use Games\Pools\UserPool;

class RefreshPoint extends BaseWorker
{
    public function Process(): array
    {
        $accessor = AccessorFactory::Main();
        $incompleteRows = $accessor->Transaction(function() use ($accessor)
        {
            $rows = $accessor->FromTable('PointOrderIncomplete')
                     ->WhereEqual('ProcessStatus',UserPoint::InComplete)
                     ->ForUpdate()
                     ->FetchAll();
            if($rows !== false)
            {
                $orderIDs = array_column($rows,'OrderID');
                $accessor->ClearCondition()
                         ->FromTable('PointOrderIncomplete')
                         ->WhereIn('OrderID',$orderIDs)
                         ->Modify(['ProcessStatus' => UserPoint::InProcess]);
                return $rows;  
            }
        });
        if($incompleteRows == false)return ["No user have to sync point."];

        $rows = $accessor->ClearCondition()
                         ->FromTable('UserPointOrder')
                         ->WhereIn('OrderID',array_column($incompleteRows,'OrderID'))
                         ->FetchAll();
        if($rows === false)return ["No user have to sync point."];
        $successDeposits = [];
        $failedDeposits = [];
        $refreshUsers = [];
        foreach($rows as $row)
        {
            $userPoint = new UserPoint($row->UserID,$row->Username);
            $info = new RefreshInfo();
            $info->amount = $row->Amount;
            $info->orderType = $row->OrderType;
            $info->orderID = str_pad((string)$row->OrderID,UserPoint::PadAmount,'0',STR_PAD_LEFT);
            $info->symbol = $row->Symbol;
            $isDone = $userPoint->RefreshPointByRefreshInfo($info);
            if($isDone) $successDeposits[] = $info->orderID;
            else $failedDeposits[] = $info->orderID;

            $refreshUsers[$row->UserID] = $row->Username;
        }

        UserPoint::FinishRefresh($successDeposits,$failedDeposits);
        $accessor->ClearCondition()->PrepareName('RefreshUserPetaToken');
        foreach($refreshUsers as $userID => $username)
        {
            $refreshPTPoint = (new UserPoint($userID,$username))->GetPoint(PointQueryValue::SymbolPT,false);
            if($refreshPTPoint !== false)
            {
                $accessor->FromTable('Users')->WhereEqual('UserID',$userID)->Modify(['PetaToken' => $refreshPTPoint * PointQueryValue::MultiplierPT]);
                UserPool::Instance()->Delete($userID);
            }
            
        }
        
        return 
        [
            'SuccessRefreshOrders' => $successDeposits,
            'FailedRefreshOrders' => $failedDeposits,
        ];
    }
}