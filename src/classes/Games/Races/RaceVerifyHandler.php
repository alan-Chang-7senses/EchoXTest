<?php

namespace Games\Races;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\Globals;
use Consts\Sessions;
use Games\Consts\RaceValue;
use Games\Consts\RaceVerifyValue;
use Games\Pools\RacePlayerPool;
use Games\Pools\RacePool;
use Games\Pools\RaceVerifyPool;
use Games\Pools\RaceVerifyScenePool;
use Games\Pools\UserPool;
use Games\Races\Holders\RaceVerifyHolder;
use Games\Users\UserHandler;
use Generators\ConfigGenerator;
use stdClass;

/*
 * Description of RaceVerifyHandler
 */

class RaceVerifyHandler {

    private static RaceVerifyHandler $instance;

    public static function Instance(): RaceVerifyHandler {
        if (empty(self::$instance)) {
            self::$instance = new RaceVerifyHandler();
        }
        return self::$instance;
    }

    private int $nowUserID;
    private stdClass|RaceVerifyHolder $raceVerifyInfo;

    public function __construct() {
        $this->nowUserID = $_SESSION[Sessions::UserID];
    }

    public function Ready(array $readyUserInfos, array $racePlayerIDs) {

        $pathInfos = $this->GetPathInfos();

        foreach ($readyUserInfos as $readyUserInfo) {
            $playerID = $readyUserInfo['player'];
            $racePlayerID = $racePlayerIDs[$playerID];

            $racePlayerHandler = new RacePlayerHandler($racePlayerID);
            $racePlayerInfo = $racePlayerHandler->GetInfo();
            $pathInfo = $pathInfos->{$racePlayerInfo->trackNumber};

            $verifyInfo = new RaceVerifyHolder();
            $verifyInfo->racePlayerID = $racePlayerID;
            $verifyInfo->verifyState = RaceVerifyValue::StateReady;
            $verifyInfo->speed = $readyUserInfo['s'];
            $verifyInfo->serverDistance = $pathInfo->begin;
            $verifyInfo->clientDistance = 0;
            $verifyInfo->isCheat = RaceVerifyValue::VerifyNotCheat;
            $verifyInfo->updateTime = $GLOBALS[Globals::TIME_BEGIN];
            $verifyInfo->startTime = 0;
            $verifyInfo->createTime = (int) $GLOBALS[Globals::TIME_BEGIN];

            RaceVerifyPool::Instance()->Add($verifyInfo);

            //RaceVerify::Instance()->AddLog($verifyInfo); //test logs                
        }
    }

    public function Start(array $racePlayerIDs) {

        foreach ($racePlayerIDs as $racePlayerID) {
            if ($this->GetInfo($racePlayerID) === false) {
                continue;
            }

            $this->raceVerifyInfo->verifyState = RaceVerifyValue::StateStart;
            $this->raceVerifyInfo->startTime = (int) $GLOBALS[Globals::TIME_BEGIN];

            $bind = [
                'VerifyState' => $this->raceVerifyInfo->verifyState,
                'StartTime' => $this->raceVerifyInfo->startTime
            ];

            RaceVerifyPool::Instance()->Update($this->raceVerifyInfo->racePlayerID, $bind);

            // RaceVerify::Instance()->AddLog($this->raceVerifyInfo); //test logs                
        }
    }

    public function EnergyAgain(int $racePlayerID): int {
        return $this->UpdatePlayer($racePlayerID, RaceVerifyValue::StateEnergyAgain, 0, 0);
    }

    public function EnergyBonus(int $racePlayerID, float $speed): int {
        return $this->UpdatePlayer($racePlayerID, RaceVerifyValue::StateEnergyBonus, $speed, 0);
    }

    public function ReachEnd(int $racePlayerID, float $distance): int {
        return $this->UpdatePlayer($racePlayerID, RaceVerifyValue::StateReachEnd, 0, $distance);
    }

    public function LaunchSelfSkill(int $racePlayerID, float $speed): int {
        return $this->UpdatePlayer($racePlayerID, RaceVerifyValue::StateSkill, $speed, 0);
    }

    public function LaunchOthersSkill(array $others, stdClass $racePlayerIDs): array {
        $result = [];
        foreach ($others as $other) {
            $playerID = $other->{'id'}; //$playerID
            $racePlayerID = $racePlayerIDs->{$playerID};
            $result[$playerID] = $this->UpdatePlayer($racePlayerID, RaceVerifyValue::StateOtherSkill, $other->s, 0);
        }
        return $result;
    }

    public function PlayerValues(int $racePlayerID, float $speed, float $distance): int {

        return $this->UpdatePlayer($racePlayerID, RaceVerifyValue::StatePlayerValue, $speed, $distance);
    }

    private function UpdatePlayer(int $racePlayerID, int $verifyState, float $speed, float $distance): int {
        if ($this->GetInfo($racePlayerID) === false) {
            return RaceVerifyValue::VerifyNoInfo;
        }

        if ($this->raceVerifyInfo->isCheat == RaceVerifyValue::VerifyCheat) {
            // 玩家已經離開比賽了,不處理作弊檢查
            return RaceVerifyValue::VerifyCheat;
        }

        $this->raceVerifyInfo->verifyState = $verifyState;

        if ($this->raceVerifyInfo->startTime !== 0) {
            $this->AccumulateDistance();
        }

        $isCheat = RaceVerifyValue::VerifyNotCheat;
        if ($verifyState == RaceVerifyValue::StatePlayerValue ||
                $verifyState == RaceVerifyValue::StateReachEnd) {

            //改變速度前要先驗證距離
            $this->raceVerifyInfo->clientDistance = round($distance, RaceVerifyValue::Decimals);
            $isCheat = $this->VerifyDistance();
        }

        if ($verifyState == RaceVerifyValue::StateSkill ||
                $verifyState == RaceVerifyValue::StateOtherSkill ||
                $verifyState == RaceVerifyValue::StatePlayerValue ||
                $verifyState == RaceVerifyValue::StateEnergyBonus) {
            $this->raceVerifyInfo->speed = $speed;
        }

        if ($isCheat == RaceVerifyValue::VerifyCheat) {
            if ($this->raceVerifyInfo->isCheat === RaceVerifyValue::VerifyNotCheat) {
                $this->raceVerifyInfo->isCheat = RaceVerifyValue::VerifyCheat;
                $this->HandleCheatUser();
            }
        }

        $this->raceVerifyInfo->verifyState = $verifyState;
        $bind = [
            'VerifyState' => $this->raceVerifyInfo->verifyState,
            'Speed' => $this->raceVerifyInfo->speed,
            'ServerDistance' => $this->raceVerifyInfo->serverDistance,
            'ClientDistance' => $this->raceVerifyInfo->clientDistance,
            'IsCheat' => $this->raceVerifyInfo->isCheat
        ];

        RaceVerifyPool::Instance()->Update($this->raceVerifyInfo->racePlayerID, $bind);

        //RaceVerify::Instance()->AddLog($this->raceVerifyInfo); //test logs

        return $isCheat;
    }

    private function GetInfo(int $racePlayerID): bool {
        $info = RaceVerifyPool::Instance()->$racePlayerID;
        if ($info !== false) {
            $this->raceVerifyInfo = $info;
            return true;
        } else {
            return false;
        }
    }

    private function GetPathInfos(): stdClass {
        $handler = new UserHandler($this->nowUserID);
        $userInfo = $handler->GetInfo();
        return RaceVerifyScenePool::Instance()->{$userInfo->scene};
    }

    private function AccumulateDistance() {
        $timeSpan = $GLOBALS[Globals::TIME_BEGIN] - $this->raceVerifyInfo->updateTime;
        $moveDistane = round($this->raceVerifyInfo->speed * $timeSpan, RaceVerifyValue::Decimals);
        $this->raceVerifyInfo->serverDistance += $moveDistane;
    }

    private function VerifyDistance(): int {

        if ($this->raceVerifyInfo->verifyState == RaceVerifyValue::StateReachEnd) {

            $pathInfos = $this->GetPathInfos();
            $racePlayerHandler = new RacePlayerHandler($this->raceVerifyInfo->racePlayerID);
            $racePlayerInfo = $racePlayerHandler->GetInfo();
            $pathInfo = $pathInfos->{$racePlayerInfo->trackNumber};
            $deviation = $pathInfo->total - $this->raceVerifyInfo->serverDistance;
        } else {
            $deviation = $this->raceVerifyInfo->clientDistance - $this->raceVerifyInfo->serverDistance;
        }
        if ($deviation > ConfigGenerator::Instance()->RaceVerifyDistance) {
            return RaceVerifyValue::VerifyCheat;
        } else {
            return RaceVerifyValue::VerifyNotCheat;
        }
    }

    private function HandleCheatUser() {
        //把玩家踢出比賽
        $racePlayerHandler = new RacePlayerHandler($this->raceVerifyInfo->racePlayerID);
        $racePlayerInfo = $racePlayerHandler->GetInfo();

        $handler = new UserHandler($racePlayerInfo->user);
        $userInfo = $handler->GetInfo();

        $accessor = new PDOAccessor(EnvVar::DBMain);
        $accessor->Transaction(function () use ($accessor, $userInfo) {

            $currentTime = $GLOBALS[Globals::TIME_BEGIN];

            $race = $accessor->FromTable('Races')->WhereEqual('RaceID', $userInfo->race)->ForUpdate()->Fetch();
            $racePlayerIDs = json_decode($race->RacePlayerIDs);
            $racePlayerID = $racePlayerIDs->{$userInfo->player};
            unset($racePlayerIDs->{$userInfo->player});
            $accessor->Modify(['RacePlayerIDs' => json_encode($racePlayerIDs)]);

            if (!empty($racePlayerID)) {
                $accessor->ClearCondition()->FromTable('RacePlayer')->WhereEqual('RacePlayerID', $racePlayerID)->Modify([
                    'Status' => RaceValue::StatusGiveUp,
                    'UpdateTime' => $currentTime,
                ]);
            }

            $accessor->ClearCondition();
            $accessor->FromTable('Users')->WhereEqual('UserID', $userInfo->id, 'id')->Modify([
                'Race' => RaceValue::NotInRace,
                'Lobby' => RaceValue::NotInLobby,
                'Room' => RaceValue::NotInRoom,
                'UpdateTime' => $currentTime,
            ]);
        });

        UserPool::Instance()->Delete($userInfo->id);
        RacePool::Instance()->Delete($userInfo->race);
        RacePlayerPool::Instance()->Delete($this->raceVerifyInfo->racePlayerID);
    }

}
