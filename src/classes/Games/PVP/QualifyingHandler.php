<?php

namespace Games\PVP;

use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\Globals;
use DateTime;
use Exception;
use Games\Accessors\QualifyingSeasonAccessor;
use Games\Consts\RaceValue;
use Games\Pools\QualifyingSeasonPool;
use Games\Pools\TicketInfoPool;
use Games\PVP\Holders\TicketInfoHolder;
use Generators\ConfigGenerator;
use Generators\DataGenerator;
use stdClass;

class QualifyingHandler
{
    //1:金幣賽 2:PT賽
    //1:金幣賽 2:PT賽 4:滿級賽
    public const Lobbies = [RaceValue::LobbyCoin, RaceValue::LobbyPT];

    public int $NowSeasonID;
    private QualifyingSeasonPool $pool;
    private TicketInfoPool $ticketPool;
    private QualifyingSeasonAccessor $pdoAccessor;

    public stdClass $info;

    public function __construct()
    {
        $this->pool = QualifyingSeasonPool::Instance();
        $this->ticketPool = TicketInfoPool::Instance();
        $this->pdoAccessor = new QualifyingSeasonAccessor();
        $this->SetInfo();
    }

    private function SetInfo()
    {

        $result = $this->pool->{ "LastID"};
        if ($result != false) {
            $this->info = $result;
            $nowtime = (int)$GLOBALS[Globals::TIME_BEGIN];
            if (($nowtime > $this->info->StartTime) && ($nowtime < $this->info->EndTime)) {
                $this->NowSeasonID = $this->info->QualifyingSeasonID;
            }
            else {
                $this->NowSeasonID = -1;
            }

        }
        else {
            $this->info = new stdClass;
            $this->info->QualifyingSeasonID = -1;
            $this->NowSeasonID = -1;            
        }
    }

    public function ChangeSeason(int $forceNewArenaID = null, bool|null $startRightNow = null): int
    {
        if ($startRightNow == null)
        {
            $startRightNow = false;
        }
        
        $lastQualifyingSeasonID = -1;
        if ($forceNewArenaID == null) {
            //正常排程換季            
            $nowtime = (int)$GLOBALS[Globals::TIME_BEGIN];

            if ($this->info->QualifyingSeasonID == -1) {
                //目前資料表沒有賽季,根據設定時間開啟賽季, 是否立即開始: $startRightNow
                $arenaID = 1;
                $newSeason = $this->GetNewSeasonInfo($startRightNow);
                $startTime = $newSeason->StartTime;
                $endTime = $newSeason->EndTime;
            }
            else {
                //目前資料表有賽季
                if ($nowtime >= $this->info->EndTime) {
                    $lastQualifyingSeasonID = $this->info->QualifyingSeasonID;
                    $arenaID = $this->info->ArenaID + 1;
                    $newSeason = $this->GetNewSeasonInfo(true);
                    $startTime = $newSeason->StartTime;
                    $endTime = $newSeason->EndTime;
                }
                else {
                    //進行中賽季, 排程時間間距太短
                    throw new Exception("進行中賽季未結束");
                }
            }
        }
        else {
            //強制換季
            if ($this->info->QualifyingSeasonID != -1) {
                $lastQualifyingSeasonID = $this->info->QualifyingSeasonID;
            }

            $newSeason = $this->GetNewSeasonInfo($startRightNow);
            $arenaID = $forceNewArenaID;
            $startTime = $newSeason->StartTime;
            $endTime = $newSeason->EndTime;
        }

        $this->pool->Delete("LastID");        
        $this->pdoAccessor->AddNewSeason($arenaID, $startTime, $endTime);
        $this->SetInfo();
        return $lastQualifyingSeasonID;
    }

    public function SendPrizes(int $qualifyingSeasonID): bool
    {
        if ($qualifyingSeasonID == -1) {
            return false;
        }
        //todo send prize 金幣和PT
        return true;
    }

    private function GetNewSeasonInfo(bool $startNow): stdClass
    {
        $startTimeValue = ConfigGenerator::Instance()->PvP_B_SeasonStartTime;
        $startTime = (new DateTime($startTimeValue))->format('U');
        $nowtime = (int)$GLOBALS[Globals::TIME_BEGIN];
        $sessionTime = $this->SeasonDurations();

        $result = new stdclass();
        if ($startTime < $nowtime) //過去時間        
        {
            $passSeasons = floor(($nowtime - $startTime) / $sessionTime);
            if ($startNow == false) {
                $passSeasons++;
            }
            $startTime = $startTime + $passSeasons * $sessionTime;
        }
        else {
            if ($startNow) {
                $diffSeasons = floor(($startTime - $nowtime) / $sessionTime) + 1;
                $startTime = $startTime - $diffSeasons * $sessionTime;
            }
        }

        $result->StartTime = $startTime;
        $result->EndTime = $startTime + $sessionTime;
        return $result;
    }

    private function SeasonDurations(): int
    {
        $weekTimeValue = ConfigGenerator::Instance()->PvP_B_WeeksPerSeacon;
        return $weekTimeValue * 604800;
    //return 2 * 60; //test for 2分鐘一季
    }

    public function CheckLobbyID(int $lobby)
    {
        if (in_array($lobby, QualifyingHandler::Lobbies) == false) {
            throw new Exception('QualifyingHandler lobby ' . $lobby . ' is invalid', ErrorCode::ParamError);
        }
    }


    public function FindItemAmount(int $userID, int $itemID): int
    {
        $bind = [
            'UserID' => $userID,
            'ItemID' => $itemID
        ];
        return $this->pdoAccessor->FindItemAmount($bind);
    }

    public function GetSeasonRemaintime(): int
    {
        $remainTime = $this->info->EndTime - $GLOBALS[Globals::TIME_BEGIN];
        if ($remainTime < 0)
            $remainTime = 0;
        return (int)$remainTime;
    }

    public function GetTicketInfo(int $userID, int $lobby): TicketInfoHolder
    {
        $this->CheckLobbyID($lobby);
        $ticketInfo = new TicketInfoHolder();
        $ticketInfo->lobby = $lobby;
        $ticketInfo->ticketID = $this->GetTicketID($lobby);
        $ticketInfo->amount = $this->FindItemAmount($userID, $ticketInfo->ticketID);
        $ticketInfo->maxReceive = $this->GetMaxTickets($lobby);
        $ticketInfo->receiveRemainTime = $this->GetRemainTicketTime($userID, $lobby);
        return $ticketInfo;
    }

    public function GetTicketID(int $lobby): int
    {
        switch ($lobby) {
            case RaceValue::LobbyCoin:
                return ConfigGenerator::Instance()->PvP_B_TicketId_1;
            case RaceValue::LobbyPT:
                return ConfigGenerator::Instance()->PvP_B_TicketId_2;
        }
        return 0;
    }

    public function GetMaxTickets(int $lobby): int
    {
        switch ($lobby) {
            case RaceValue::LobbyCoin:
                return ConfigGenerator::Instance()->PvP_B_MaxTickets_1;
            case RaceValue::LobbyPT:
                return ConfigGenerator::Instance()->PvP_B_MaxTickets_2;
        }
        return 0;
    }

    public function GetSceneID(int $lobby): int
    {
        switch ($lobby) {
            case RaceValue::LobbyCoin:
                return $this->info->CoinScene;
            case RaceValue::LobbyPT:
                return $this->info->PTScene;
        }
        return 0;
    }

    public function GetPetaLimitLevel(int $lobby): int
    {
        switch ($lobby) {
            case RaceValue::LobbyCoin:
                return ConfigGenerator::Instance()->PvP_B_PetaLvLimit_1;
        }
        return 0;
    }


    public function SetNextTokenTime(int $userID, int $lobby): bool
    {
        $keyValue = "";
        $updateColumn = "";
        switch ($lobby) {
            case RaceValue::LobbyCoin:
                $keyValue = 'Ticket_Coin';
                $updateColumn = "CoinTime";
                break;
            case RaceValue::LobbyPT:
                $keyValue = 'Ticket_PT';
                $updateColumn = "PTTime";
                break;
        }

        $range = $this->pdoAccessor->GetRange($keyValue);
        if ($range != false) {
            $nowSeconds = DataGenerator::TodaySecondByTimezone(getenv(EnvVar::TimezoneDefault));
            $setSeconds = -1;
            for ($i = 0; $i < count($range); $i++) {
                if ($range[$i] < $nowSeconds) {
                    continue;
                }

                $setSeconds = $range[$i];
                break;
            }

            if ($setSeconds == -1) {
                $setSeconds = 86400 + $range[0];
            }

            $setTime = DataGenerator::SetTodaySecondsToTimezone($setSeconds, getenv(EnvVar::TimezoneDefault));
            $this->ticketPool->Delete($userID);
            return $this->pdoAccessor->UpdateTicketInfo($userID, [$updateColumn => $setTime]);

        }
        else {
            return false;
        }
    }

    public function GetRemainTicketTime(int $userID, int $lobby): int
    {
        $userRewardTimes = $this->ticketPool->{ $userID};

        if ($userRewardTimes != false) {
            $resultTime = 0;
            $nowtime = (int)$GLOBALS[Globals::TIME_BEGIN];
            switch ($lobby) {
                case RaceValue::LobbyCoin:
                    $resultTime = $userRewardTimes->CoinTime - $nowtime;
                    break;
                case RaceValue::LobbyPT:
                    $resultTime = $userRewardTimes->PTTime - $nowtime;
                    ;
                    break;
            }
            if ($resultTime < 0) {
                $resultTime = 0;
            }
            return $resultTime;
        }
        else {
            return 0;
        }
    }

    public function GetRank(int $lobby): stdclass
    {
        //todo
        $result = new stdclass;
        switch ($lobby) {
            case RaceValue::LobbyCoin:
                $result->raceAmount = 12;
                $result->aveRank = "1.11";
                $result->rank = "5";
                break;
            case RaceValue::LobbyPT:
                $result->raceAmount = 34;
                $result->aveRank = "3.21";
                $result->rank = "15";
                break;
        }
        return $result;
    }
}