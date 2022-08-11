<?php

namespace Games\Pools;

use Accessors\PoolAccessor;
use Games\Accessors\MailsAccessor;
use stdClass;

class MailsInfoPool extends PoolAccessor{
    
    private static MailsInfoPool $instance;
    
    public static function Instance() : MailsInfoPool {
        if(empty(self::$instance)) self::$instance = new MailsInfoPool ();
        return self::$instance;
    }

    protected string $keyPrefix = 'mailsInfo_';

    public function FromDB(int|string $mailsID): stdClass|false {
        $mailsAccessor = new MailsAccessor();
        $rows = $mailsAccessor->GetMailsInfo($mailsID);

        $holder = new stdClass();
        foreach($rows as $row){           
            $holder->{$row->Lang} = $row;
        }
        return $holder;
    }
}