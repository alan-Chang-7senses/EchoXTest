<?php

namespace Games\Pools\Store;

use Accessors\PDOAccessor;
use Accessors\PoolAccessor;
use Consts\EnvVar;
use Consts\Globals;
use Games\Store\Holders\StoreTradesHolder;
use stdClass;

/*
 * Description of StoreTradesPool
 * Description of 交易資訊
 */

class StoreTradesPool extends PoolAccessor {

    private static StoreTradesPool $instance;

    public static function Instance(): StoreTradesPool {
        if (empty(self::$instance)) {
            self::$instance = new StoreTradesPool();
        }
        return self::$instance;
    }

    protected string $keyPrefix = 'storetrades_';

    public function FromDB(int|string $id): stdClass|false {

        $accessor = new PDOAccessor(EnvVar::DBMain);
        $row = $accessor->FromTable('StoreTrades')->WhereEqual("TradeID", $id)->Fetch();

        if ($row == false) {
            return false;
        }

        $holder = new StoreTradesHolder ();
        $holder->tradeID = $row->TradeID;
        $holder->userID = $row->UserID;
        $holder->storeID = $row->StoreID;
        $holder->status = $row->Status;
        $holder->storeType = $row->StoreType;
        $holder->cPIndex = $row->CPIndex;
        $holder->remainInventory = $row->RemainInventory;
        $holder->updateTime = $row->UpdateTime;

        return $holder;
    }

    protected function SaveUpdate(stdClass $data, array $bind): stdClass {

        $bind['UpdateTime'] = (int) $GLOBALS[Globals::TIME_BEGIN];

        foreach ($bind as $key => $value) {
            $data->{lcfirst($key)} = $value;
        }
        $accessor = new PDOAccessor(EnvVar::DBMain);
        $accessor->FromTable('StoreTrades')->WhereEqual("TradeID", $data->tradeID)->Modify($bind);
        return $data;
    }

}
