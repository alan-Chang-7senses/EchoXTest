<?php

namespace Games\Pools;

use Accessors\PDOAccessor;
use Accessors\PoolAccessor;
use Consts\EnvVar;
use stdClass;

/*
 * Description of RaceVerifyScenePool
 */

class RaceVerifyScenePool extends PoolAccessor {

    private static RaceVerifyScenePool $instance;

    public static function Instance(): RaceVerifyScenePool {
        if (empty(self::$instance)) {
            self::$instance = new RaceVerifyScenePool ();
        }
        return self::$instance;
    }

    protected string $keyPrefix = 'raceSceneVerify_';
    private string $tablename = "RaceSceneVerify";

    public function FromDB(int|string $sceneID): stdClass|false {

        $accessor = new PDOAccessor(EnvVar::DBStatic);
        $rows = $accessor->FromTable($this->tablename)->WhereEqual('SceneID', $sceneID)->FetchAll();

        if ($rows !== false) {
            $result = new stdClass();
            foreach ($rows as $row) {
                $path = new stdClass();
                $path->begin = $row->BeginDistance;
                $path->total = $row->TotalDistance;
                $result->{$row->TrackNumber} = $path;
            }
            return $result;
        } else {
            return false;
        }
    }

}
