<?php

namespace Games\Pools\Store;

use Accessors\PDOAccessor;
use Accessors\PoolAccessor;
use Consts\EnvVar;
use Games\Store\Holders\StorePurchaseHolder;
use stdClass;

/*
 * Description of StorePurchasePool
 * Description of 儲值商店
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

    public function FromDB(int|string $pIndex): stdClass|false {

        $accessor = new PDOAccessor(EnvVar::DBStatic);
        $row = $accessor->FromTable('StorePurchase')->WhereEqual('PIndex', $pIndex)->Fetch();
        
        if ($row == false)
        {
            return false;
        }

        $holder = new StorePurchaseHolder ();
        $holder->pIndex = $row->PIndex;
        $holder->groupID = $row->GroupID;
        $holder->purchaseID = $row->PurchaseID;
        $holder->itemID = $row->ItemID;
        $holder->amount = $row->Amount;
        $holder->productID = $row->ProductID;
        return $holder;
    }

}
