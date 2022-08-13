<?php

namespace Games\Pools;


use Accessors\PoolAccessor;
use Games\Accessors\MailsAccessor;
use stdClass;

class UserMailItemsPool extends PoolAccessor{
    
    private static UserMailItemsPool $instance;
    
    public static function Instance() : UserMailItemsPool {
        if(empty(self::$instance)) self::$instance = new UserMailItemsPool ();
        return self::$instance;
    }

    protected string $keyPrefix = 'userMailItems_';

    public function FromDB(int|string $userMailID): stdClass|false {
        $mailsAccessor = new MailsAccessor();
        $rows = $mailsAccessor->GetUserMailItems($userMailID);

        $holder = new stdClass();
        $holder->rows = $rows;
        return $holder;
    }
}