<?php

namespace Games\Pools\Store;

use Accessors\PDOAccessor;
use Accessors\PoolAccessor;
use Consts\EnvVar;
use Games\Store\Holders\StoreProductInfoHolder;
use stdClass;

/**
 * Description of StoreProductInfoPool
 * Description of 儲值商店品項資訊
 */
class StoreProductInfoPool extends PoolAccessor {

    private static StoreProductInfoPool $instance;

    public static function Instance(): StoreProductInfoPool {
        if (empty(self::$instance)) {
            self::$instance = new StoreProductInfoPool();
        }
        return self::$instance;
    }

    protected string $keyPrefix = 'storeproductinfo_';

    public function FromDB(int|string $id): stdClass|false {

        $accessor = new PDOAccessor(EnvVar::DBStatic);
        $row = $accessor->FromTable('StoreProductInfo')->WhereEqual("ProductID", $id)->Fetch();
        if ($row == false) {
            return false;
        }

        $holder = new StoreProductInfoHolder ();
        $holder->productID = $row->ProductID;
        $holder->productName = $row->ProductName;
        $holder->amount = $row->Amount;
        $holder->currency = $row->Currency;

        return $holder;
    }

}
