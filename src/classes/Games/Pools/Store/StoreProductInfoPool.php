<?php

namespace Games\Pools\Store;

use Accessors\PDOAccessor;
use Accessors\PoolAccessor;
use Consts\EnvVar;
use Games\Store\Models\StoreProductInfoModel;
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
        $rows = $accessor->FromTable('StoreProductInfo')->WhereEqual("ProductID", $id)->FetchAll();
        if ($rows == false) {
            return false;
        }

        $result = new stdClass();
        foreach ($rows as $row) {
            $holder = new StoreProductInfoModel();
            $holder->ProductID = $row->ProductID;
            $holder->MultiNo = $row->MultiNo;
            $holder->Price = $row->Price;
            $holder->ISOCurrency = $row->ISOCurrency;            
            $result->{$row->ISOCurrency} = $holder;
        }

        return $result;
    }

}
