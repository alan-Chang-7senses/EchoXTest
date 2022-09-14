<?php

namespace Games\Pools;

use Accessors\PDOAccessor;
use Accessors\PoolAccessor;
use Consts\EnvVar;
use Consts\Globals;
use Games\Races\Holders\RaceVerifyHolder;
use stdClass;

/*
 * Description of RaceVerifyPool
 */

class RaceVerifyPool extends PoolAccessor {

    private static RaceVerifyPool $instance;

    public static function Instance(): RaceVerifyPool {
        if (empty(self::$instance)) {
            self::$instance = new RaceVerifyPool ();
        }
        return self::$instance;
    }

    protected string $keyPrefix = 'raceVerify_';
    private string $tablename = "RaceVerify";
    private string $checkTablename = "CheckVerify";

    public function FromDB(int|string $racePlayerID): stdClass|false {

        $accessor = new PDOAccessor(EnvVar::DBMain);
        $row = $accessor->FromTable($this->tablename)->WhereEqual('RacePlayerID', $racePlayerID)->Fetch();
        if ($row !== false) {
            $holder = new RaceVerifyHolder();
            $holder->racePlayerID = $row->RacePlayerID;
            $holder->verifyStage = $row->VerifyStage;
            $holder->speed = $row->Speed;
            $holder->serverDistance = $row->ServerDistance;
            $holder->clientDistance = $row->ClientDistance;
            $holder->updateTime = $row->UpdateTime;
            $holder->startTime = $row->StartTime;
            $holder->createTime = $row->CreateTime;
            return $holder;
        } else {
            return false;
        }
    }

    protected function SaveNew(stdClass|bool $nowData, RaceVerifyHolder $newData): stdClass {

        if ($nowData == false) {
            $bind = [];
            foreach ($newData as $key => $value) {
                $bind[ucfirst($key)] = $value;
            }
            $accessor = new PDOAccessor(EnvVar::DBMain);
            $accessor->FromTable($this->tablename)->Add($bind);
            return $newData;
        }
    }

    protected function SaveUpdate(stdClass $nowData, array $values): stdClass {

        $accessor = new PDOAccessor(EnvVar::DBMain);
        $accessor->Transaction(function () use ($accessor, $nowData, $values) {

            $accessor->ClearCondition()->FromTable($this->tablename)->WhereEqual('RacePlayerID', $nowData->racePlayerID)->ForUpdate();

            $values['updateTime'] = $GLOBALS[Globals::TIME_BEGIN];
            $bind = [];
            foreach ($values as $key => $value) {
                $bind[ucfirst($key)] = $value;
                $nowData->$key = $value;
            }
            $accessor->ClearCondition()->FromTable($this->tablename)->WhereEqual('RacePlayerID', $nowData->racePlayerID)->Modify($bind);
        });

        return $nowData;
    }

}
