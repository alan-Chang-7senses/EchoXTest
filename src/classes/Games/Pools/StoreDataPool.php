<?php

namespace Games\Pools;

use Accessors\PoolAccessor;
use stdClass;

/*
 * Description of StoreDataPool
 */

class StoreDataPool extends PoolAccessor {

    private static StoreDataPool $instance;

    public static function Instance(): StoreDataPool {
        if (empty(self::$instance)) {
            self::$instance = new StoreDataPool ();
        }
        return self::$instance;
    }

    protected string $keyPrefix = 'storedata_';

    public function FromDB(int|string $id): stdClass|false {
//$accessor = new xxAccessor();

        $holder = new stdClass();
//todo
//

        return $holder;
    }

}
