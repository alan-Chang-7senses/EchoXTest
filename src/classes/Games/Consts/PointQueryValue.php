<?php

namespace Games\Consts;

class PointQueryValue
{
    const CodeSuccess = '000-00';
    
    const SymbolPT = 'PT';
    const OrderIDMin = 100000;
    const URLAddPoint = 'v1/deposit-orders';
    const URLGetUserBalance = 'v1/users/balances';

    const StatusReturnError = 'ERROR';
    const StatusNew = 'NEW';
    const StatusComplete = 'COMPLETE';

    const OrderTypeDeposit = 'DEPOSIT';

    const ReturnStatus = 
    [
        self::StatusNew => 0,
        self::StatusComplete => 1,
    ];

    const OrderTypes = 
    [
        self::OrderTypeDeposit => 1,
    ];

}