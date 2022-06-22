<?php

namespace Games\Pools;


use Accessors\PoolAccessor;
use Games\Accessors\MailsAccessor;
use stdClass;

class MailsRewardPool extends PoolAccessor{
    
    private static MailsRewardPool $instance;
    
    public static function Instance() : MailsRewardPool {
        if(empty(self::$instance)) self::$instance = new MailsRewardPool ();
        return self::$instance;
    }

    protected string $keyPrefix = 'mailsReward_';

    public function FromDB(int|string $id): stdClass|false {
        $mailsAccessor = new MailsAccessor();
        $rows = $mailsAccessor->rowsMailsRewards($id);

        $holder = new stdClass();
        $holder->rows = $rows;
        return $holder;
    }
}