<?php

namespace Processors;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\Globals;
use Exception;
use Helpers\InputHelper;
use Holders\ResultData;

class BlanceTransfer extends BaseProcessor
{
    protected bool $mustSigned = false;
    const ExceptionUserNotExit = 1001;
    const ExceptionBalanceNotEnough = 1002;

    public function Process(): ResultData
    {
        $accessor = new PDOAccessor(EnvVar::DBMain);

        $transferUserId = 1;
        $receiveUserId = InputHelper::post('receiveUserId');
        $transferAmount = InputHelper::post('transferAmount');

        //使用交易
        $logBinds = $accessor->Transaction(function() use ($accessor,$transferUserId,$receiveUserId,$transferAmount)
        {
            $rows = $accessor->FromTable('balances')
                     ->WhereIn('user_id',[$transferUserId,$receiveUserId])
                     ->ForUpdate() //鎖住轉出與轉入方資料
                     ->FetchAll();
            if($rows === false || count($rows) < 2)throw new Exception("UserId dosen't exist.",self::ExceptionUserNotExit);
            $userBalanceData = [];
            foreach($rows as $row)
            {
                $userBalanceData[$row->user_id] = $row;
            }
            if($userBalanceData[$transferUserId]->balance < $transferAmount)
            {
                throw new Exception("User balance not enough",self::ExceptionBalanceNotEnough);
            }
            $userBalanceData[$transferUserId]->balance -= $transferAmount;
            $userBalanceData[$receiveUserId]->balance += $transferAmount;
            $binds = [];
            foreach($userBalanceData as $row)
            {
                $binds[] = 
                [
                    'id' => $row->id,
                    'user_id' => $row->user_id,
                    'balance' => $row->balance,
                    'updated_at' => $GLOBALS[Globals::TIME_BEGIN],
                    'created_at' => $row->created_at,
                ];
                $logBinds[] = 
                [
                    'user_id' => $row->user_id,
                    'transaction_type' => $row->id == $transferUserId ? 'CREDIT' : 'DEBIT',
                    'amount' => $transferAmount,
                    'created_at' => $GLOBALS[Globals::TIME_BEGIN],
                    'updated_at' => $GLOBALS[Globals::TIME_BEGIN],
                ];
            }
            $accessor->ClearCondition()->FromTable('balances')->AddAll($binds,true);
            return $logBinds;
        });
        $accessor->ClearCondition()->FromTable('balance_logs')->AddAll($logBinds);
        
        $results = new ResultData(ErrorCode::Success);
        return $results;
    }
}    