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

    public function FromDB(int|string $racePlayerID): stdClass|false {

        $accessor = new PDOAccessor(EnvVar::DBMain);
        $row = $accessor->FromTable($this->tablename)->WhereEqual('RacePlayerID', $racePlayerID)->Fetch();
        if ($row !== false) {
            $holder = new RaceVerifyHolder();
            $holder->racePlayerID = $row->RacePlayerID;
            $holder->verifyState = $row->VerifyState;
            $holder->speed = $row->Speed;
            $holder->serverDistance = $row->ServerDistance;
            $holder->clientDistance = $row->ClientDistance;
            $holder->isCheat = $row->IsCheat;
            $holder->updateTime = $row->UpdateTime;
            $holder->startTime = $row->StartTime;
            $holder->createTime = $row->CreateTime;
            return $holder;
        } else {
            return false;
        }
    }

    public function Add(RaceVerifyHolder $newData) {
        $bind = [];
        foreach ($newData as $key => $value) {
            $bind[ucfirst($key)] = $value;
        }
        $accessor = new PDOAccessor(EnvVar::DBMain);
        $accessor->FromTable($this->tablename)->Add($bind);
    }

    public function Update(int|string $racePlayerID, array $bind) {

        $accessor = new PDOAccessor(EnvVar::DBMain);
        $accessor->Transaction(function () use ($accessor, $racePlayerID, $bind) {

            $accessor->FromTable($this->tablename)->WhereEqual('RacePlayerID', $racePlayerID)->ForUpdate()->Fetch();
            $bind['UpdateTime'] = $GLOBALS[Globals::TIME_BEGIN];
            $accessor->ClearCondition()->FromTable($this->tablename)->WhereEqual('RacePlayerID', $racePlayerID)->Modify($bind);
        });
        $this->Delete($racePlayerID);
    }

}
