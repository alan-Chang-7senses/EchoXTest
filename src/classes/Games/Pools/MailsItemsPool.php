<?php

namespace Games\Pools;

use Accessors\PoolAccessor;
use Games\Accessors\MailsAccessor;
use stdClass;

class MailsItemsPool extends PoolAccessor{
    
    private static MailsItemsPool $instance;
    
    public static function Instance() : MailsItemsPool {
        if(empty(self::$instance)) self::$instance = new MailsItemsPool ();
        return self::$instance;
    }

    protected string $keyPrefix = 'mailsItems_';

    public function FromDB(int|string $mailsID): stdClass|false {
        $mailsAccessor = new MailsAccessor();
        return $mailsAccessor->GetMailsItems($mailsID);
    }
}