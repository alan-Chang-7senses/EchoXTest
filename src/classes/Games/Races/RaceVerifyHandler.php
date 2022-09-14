<?php

namespace Games\Races;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\Globals;
use Consts\Sessions;
use Games\Consts\RaceVerifyValue;
use Games\Pools\RaceVerifyPool;
use Games\Races\Holders\RaceVerifyHolder;
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

    private stdClass|false $raceVerifyInfo;

    public function Ready(array $readyUserInfos, array $racePlayerIDs) {

        foreach ($readyUserInfos as $readyUserInfo) {
            $playerID = $readyUserInfo['player'];
            $racePlayerID = $racePlayerIDs[$playerID];

            $verifyInfo = new RaceVerifyHolder();
            $verifyInfo->racePlayerID = $racePlayerID;
            $verifyInfo->verifyStage = RaceVerifyValue::VerifyStageReady;
            $verifyInfo->speed = $readyUserInfo['s'];
            $verifyInfo->serverDistance = 0;
            $verifyInfo->clientDistance = 0;
            $verifyInfo->updateTime = $GLOBALS[Globals::TIME_BEGIN];
            $verifyInfo->startTime = 0;
            $verifyInfo->createTime = (int) $GLOBALS[Globals::TIME_BEGIN];
            RaceVerifyPool::Instance()->Save($racePlayerID, "New", $verifyInfo);

            RaceVerify::Instance()->AddLog($verifyInfo); //test logs                
        }
    }

    public function Start(array $racePlayerIDs) {

        foreach ($racePlayerIDs as $racePlayerID) {
            if ($this->GetInfo($racePlayerID) === false) {
                RaceVerify::Instance()->AddTestLog("racePlayerID(" . $racePlayerID . ") does not start");
                continue;
            }

            $this->raceVerifyInfo->startTime = (int) $GLOBALS[Globals::TIME_BEGIN];
            RaceVerifyPool::Instance()->Save($this->raceVerifyInfo->racePlayerID, "Update", [
                'verifyStage' => RaceVerifyValue::VerifyStageStart,
                'startTime' => $this->raceVerifyInfo->startTime
            ]);

            RaceVerify::Instance()->AddLog($this->raceVerifyInfo); //test logs                
        }
    }

    public function EnergyAgain(int $racePlayerID): int {
        return $this->UpdatePlayer($racePlayerID, RaceVerifyValue::VerifyStageEnergyAgain, 0, 0);
    }

    public function ReachEnd(int $racePlayerID, float $distance): int {
        return $this->UpdatePlayer($racePlayerID, RaceVerifyValue::VerifyStageReachEnd, 0, $distance);
    }

    public function LaunchSelfSkill(int $racePlayerID, float $speed): int {
        return $this->UpdatePlayer($racePlayerID, RaceVerifyValue::VerifyStageSkill, $speed, 0);
    }

    public function LaunchOthersSkill(array $others, stdClass $racePlayerIDs): array {
        $result = [];
        foreach ($others as $other) {
            $playerID = $other->{'id'}; //$playerID
            $racePlayerID = $racePlayerIDs->{$playerID};
            $result[$playerID] = $this->UpdatePlayer($racePlayerID, RaceVerifyValue::VerifyStageOtherSkill, $other->s, 0);
        }
        return $result;
    }

    public function PlayerValues(int $racePlayerID, float $speed, float $distance): int {

        return $this->UpdatePlayer($racePlayerID, RaceVerifyValue::VerifyStagePlayerValue, $speed, $distance);
    }

    public function GetMoveDistance(int $racePlayerID): int {
        if ($this->GetInfo($racePlayerID) === false) {
            return 0;
        }
        return $this->raceVerifyInfo->serverDistance;
    }

    private function UpdatePlayer(int $racePlayerID, int $verifyStage, float $speed, float $distance): int {
        if ($this->GetInfo($racePlayerID) === false) {
            return RaceVerifyValue::VerifyNoInfo;
        }

        if ($this->raceVerifyInfo->startTime !== 0) {

            $this->AccumulateDistance();
        } else {
            // 還沒開始前,Client 校正 起始位置, todo 理應要表定
            if ($verifyStage === RaceVerifyValue::VerifyStagePlayerValue) {
                $this->raceVerifyInfo->serverDistance = $distance;
            }
        }

        $isCheat = RaceVerifyValue::VerifyNotCheat;
        if ($verifyStage === RaceVerifyValue::VerifyStagePlayerValue ||
                $verifyStage === RaceVerifyValue::VerifyStageReachEnd) {
            $this->raceVerifyInfo->clientDistance = $distance;
            $isCheat = $this->VerifyDistance();
        }

        if ($verifyStage === RaceVerifyValue::VerifyStageSkill ||
                $verifyStage === RaceVerifyValue::VerifyStageOtherSkill ||
                $verifyStage === RaceVerifyValue::VerifyStagePlayerValue) {
            //改變速度前要先驗證
            $this->raceVerifyInfo->speed = $speed;
        }

        $this->raceVerifyInfo->verifyStage = $verifyStage;
        RaceVerifyPool::Instance()->Save($this->raceVerifyInfo->racePlayerID, "Update", [
            'verifyStage' => $this->raceVerifyInfo->verifyStage,
            'speed' => $this->raceVerifyInfo->speed,
            'serverDistance' => $this->raceVerifyInfo->serverDistance,
            'clientDistance' => $this->raceVerifyInfo->clientDistance,
        ]);

        RaceVerify::Instance()->AddLog($this->raceVerifyInfo); //test logs
        //return $this->raceVerifyInfo->isCheat;
        if ($isCheat === RaceVerifyValue::VerifyCheat) {
            //logout?
            $this->HandleCheatUser();
        }

        return $isCheat;
    }

    private function GetInfo(int $racePlayerID): bool {
        $this->raceVerifyInfo = RaceVerifyPool::Instance()->$racePlayerID;
        $result = $this->raceVerifyInfo !== false;
        return $result;
    }

    private function AccumulateDistance() {
        $timeSpan = $GLOBALS[Globals::TIME_BEGIN] - $this->raceVerifyInfo->updateTime;
        $moveDistane = (float) number_format($this->raceVerifyInfo->speed * $timeSpan, RaceVerifyValue::Decimals);
        $this->raceVerifyInfo->serverDistance += $moveDistane;
    }

    private function VerifyDistance(): int {
        $deviation = $this->raceVerifyInfo->clientDistance - $this->raceVerifyInfo->serverDistance;
        if ($deviation > ConfigGenerator::Instance()->RaceVerifyDistance) {
            return RaceVerifyValue::VerifyCheat;
        } else {
            return RaceVerifyValue::VerifyNotCheat;
        }
    }

    private function HandleCheatUser() {
        $racePlayerHandler = new RacePlayerHandler($this->raceVerifyInfo->racePlayerID);
        $racePlayerInfo = $racePlayerHandler->GetInfo();
        if ($racePlayerInfo->user !== $_SESSION[Sessions::UserID]) {
            RaceVerify::Instance()->AddTestLog("User different"); //test logs
        } else {
            session_destroy();

            $accessor = new PDOAccessor(EnvVar::DBMain);
            $accessor->FromTable('RecoveryData')->WhereEqual('PlayerID', $racePlayerInfo->player)->Modify([
                'MoveDistance' => $this->raceVerifyInfo->serverDistance]);

            RaceVerify::Instance()->AddTestLog("Change Client Distance form" . $this->raceVerifyInfo->clientDistance . " -> " .
                    $this->raceVerifyInfo->serverDistance); //test logs            
        }
    }

}
