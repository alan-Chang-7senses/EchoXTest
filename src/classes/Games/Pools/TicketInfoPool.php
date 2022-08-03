<?php

namespace Games\Pools;

use stdClass;
use Accessors\PoolAccessor;
use Games\Accessors\QualifyingSeasonAccessor;

class TicketInfoPool extends PoolAccessor
{
    protected string $keyPrefix = 'TicketInfoPool_';
    private static TicketInfoPool $instance;

    public static function Instance() : TicketInfoPool{
        if(empty(self::$instance)) self::$instance = new TicketInfoPool ();
        return self::$instance;
    }
    
	function FromDB(int|string $userId):stdClass|false  {        
        $pdoAccessor = new QualifyingSeasonAccessor();        
        return $pdoAccessor->GetUserTicketInfo($userId);
	}
}