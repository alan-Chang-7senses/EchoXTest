<?php

namespace Games\Pools\Store;

use Accessors\PDOAccessor;
use Accessors\PoolAccessor;
use Consts\EnvVar;
use Games\Store\Holders\StoreDataHolder;
use stdClass;

/*
 * Description of StoreDataPool
 * Description of 商店資訊
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

    public function FromDB(int|string $storeID): stdClass|false {

        $accessor = new PDOAccessor(EnvVar::DBStatic);
        $row = $accessor->FromTable('StoreData')->WhereEqual('StoreID', $storeID)->WhereEqual('IsOpen', 1)->Fetch();
        if (empty($row)) {
            return false;
        }

        $holder = new StoreDataHolder ();
        $holder->storeID = $row->StoreID;
        $holder->multiName = $row->MultiName;
        $holder->storeType = $row->StoreType;
        $holder->uIStyle = $row->UIStyle;
        $holder->fixedGroup = $row->FixedGroup;
        $holder->stochasticGroup = $row->StochasticGroup ?: -1;
        $holder->refreshCount = $row->RefreshCount;
        $holder->refreshCost = $row->RefreshCost;
        $holder->refreshCostCurrency = $row->RefreshCostCurrency;
        return $holder;
    }

}
