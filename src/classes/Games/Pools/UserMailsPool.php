<?php

namespace Games\Pools;

use Accessors\PoolAccessor;
use Games\Accessors\MailsAccessor;
use stdClass;

class  UserMailsPool extends PoolAccessor{
    
    private static UserMailsPool $instance;
    
    public static function Instance() : UserMailsPool {
        if(empty(self::$instance)) self::$instance = new UserMailsPool ();
        return self::$instance;
    }

    protected string $keyPrefix = 'mails_';

    public function FromDB(int|string $userID): stdClass|false {
        
        $mailsAccessor = new MailsAccessor();
        $rows = $mailsAccessor->GetUserMails($userID);
      
        $holder = new stdClass();
        $holder->rows = $rows;
        return $holder;
    }
}