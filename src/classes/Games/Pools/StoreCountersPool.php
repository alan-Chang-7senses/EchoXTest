<?php

namespace Games\Pools;

use Accessors\PoolAccessor;
use stdClass;

/*
 * Description of StoreCountersPool
 */

class StoreCountersPool extends PoolAccessor{
    
    private static StoreCountersPool $instance;
    
    public static function Instance() : StoreCountersPool {
        if(empty(self::$instance))
        {
            self::$instance = new StoreCountersPool ();
        }
        return self::$instance;
    }

    protected string $keyPrefix = 'storecounters_';

    public function FromDB(int|string $id): stdClass|false {
        //$accessor = new xxAccessor();
        
        $holder = new stdClass();
		//todo
        //
       
        return $holder;
    }
}

