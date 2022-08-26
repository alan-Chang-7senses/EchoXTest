<?php

namespace Games\Pools;

use Accessors\PDOAccessor;
use Accessors\PoolAccessor;
use Consts\EnvVar;
use stdClass;

/**
 * Description of HintTextPool
 */
class HintTextPool extends PoolAccessor {

    private static HintTextPool $instance;

    public static function Instance(): HintTextPool {
        if (empty(self::$instance)) {
            self::$instance = new HintTextPool ();
        }
        return self::$instance;
    }

    protected string $keyPrefix = 'hintText_';

    public function FromDB(int|string $hintID): stdClass|false {

        $accessor = new PDOAccessor(EnvVar::DBStatic);
        $accessor->ClearAll();
        $rows = $accessor->FromTable('HintText')->WhereEqual('HintID', $hintID)->FetchAll();

        if ($rows !== false) {
            $holder = new stdClass();
            foreach ($rows as $row) {
                $holder->{$row->Lang} = $row;
            }
            return $holder;
        } else {
            return false;
        }
    }

}
