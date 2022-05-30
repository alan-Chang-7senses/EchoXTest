<?php

namespace Games\Pools;

use Accessors\PoolAccessor;
use Games\Accessors\NoticeAccessor;
use Games\Consts\NoticeValue;
use stdClass;
/**
 * Description of MarqueePool
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class MarqueePool extends PoolAccessor{
    
    private static MarqueePool $instance;
    
    public static function Instance() : MarqueePool {
        if(empty(self::$instance)) self::$instance = new MarqueePool ();
        return self::$instance;
    }

    protected string $keyPrefix = 'marquee_';

    public function FromDB(int|string $lang): stdClass|false {
        
        $accessor = new NoticeAccessor();
        $rows = $accessor->rowsMarqueeAssoc($lang, NoticeValue::StatusEnabled);
        
        $holder = new stdClass();
        $holder->lang = $lang;
        $holder->contents = array_column($rows, 'Content');
        
        return $holder;
    }
}
