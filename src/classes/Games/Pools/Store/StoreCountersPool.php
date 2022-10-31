<?php

namespace Games\Pools\Store;

use Accessors\PDOAccessor;
use Accessors\PoolAccessor;
use Consts\EnvVar;
use Games\Store\Holders\StoreCountersHolder;
use stdClass;

/*
 * Description of StoreCountersPool
 * Description of 一般商店
 */

class StoreCountersPool extends PoolAccessor {

    private static StoreCountersPool $instance;

    public static function Instance(): StoreCountersPool {
        if (empty(self::$instance)) {
            self::$instance = new StoreCountersPool();
        }
        return self::$instance;
    }

    protected string $keyPrefix = 'storecounters_';

    public function FromDB(int|string $cIndex): stdClass|false {

        $accessor = new PDOAccessor(EnvVar::DBStatic);
        $row = $accessor->FromTable('StoreCounters')->WhereEqual("CIndex", $cIndex)->Fetch();

        $holder = new StoreCountersHolder ();
        $holder->cIndex = $row->CIndex;
        $holder->groupID = $row->GroupID;
        $holder->counterID = $row->CounterID;
        $holder->itemID = $row->ItemID;
        $holder->amount = $row->Amount;
        $holder->inventory = $row->Inventory;
        $holder->price = $row->Price;
        $holder->currency = $row->Currency;
        $holder->promotion = $row->Promotion;

        return $holder;
    }

}
