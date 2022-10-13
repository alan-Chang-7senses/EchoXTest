<?php

namespace Games\Pools;

use Accessors\PoolAccessor;
use stdClass;

/*
 * Description of StorePurchasePool
 */

class StorePurchasePool extends PoolAccessor {

    private static StorePurchasePool $instance;

    public static function Instance(): StorePurchasePool {
        if (empty(self::$instance)) {
            self::$instance = new StorePurchasePool();
        }
        return self::$instance;
    }

    protected string $keyPrefix = 'storepurchase_';

    public function FromDB(int|string $id): stdClass|false {

        //$accessor = new xxAccessor();

        $holder = new stdClass();
        //todo
        //



        return $holder;
    }

}
