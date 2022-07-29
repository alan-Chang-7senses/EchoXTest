<?php

namespace Games\PVP;

use DateTime;
use stdClass;
use Exception;
use Consts\Globals;
use Consts\ErrorCode;
use Generators\ConfigGenerator;
use Games\Pools\QualifyingSeasonPool;
use Games\PVP\Holders\TicketinfoHolder;
use Games\Accessors\QualifyingSeasonAccessor;

class QualifyingHandler
{
    public const Lobbies = [1, 2];
    private QualifyingSeasonPool $pool;
    private QualifyingSeasonAccessor $pdoAccessor;

    public stdClass $info;

    public function __construct()
    {
        $this->pool = QualifyingSeasonPool::Instance();
        $this->pdoAccessor = new QualifyingSeasonAccessor();
        $this->ResetInfo();
    }

    private function ResetInfo()
    {
        $this->pool->Delete("LastID");

        $result = $this->pool->{ "LastID"};
        if ($result != false) {
            $this->info = $result;
        }
        else {
            $this->info = new stdClass;
            $this->info->QualifyingSeasonID = -1;
        }
    }

    public function ChangeSeason(int $forceNewArenaID = null, bool $startRightNow): int
    {
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

        $this->pdoAccessor->AddNewSeason($arenaID, $startTime, $endTime);
        $this->ResetInfo();
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

    public function NoSeasonData(): bool
    {
        return ($this->info->QualifyingSeasonID == -1);
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

    public function GetTokenInfo(int $userID, int $lobby): TicketinfoHolder
    {
        $this->CheckLobbyID($lobby);
        $ticketInfo = new TicketinfoHolder();
        $ticketInfo->ticketID = $this->GetTokenID($lobby);
        $ticketInfo->amount = $this->FindItemAmount($userID, $ticketInfo->ticketID);
        $ticketInfo->maxReceive = $this->GetMaxTokens($lobby);
        $ticketInfo->receiveRemainTime = $this->GetRemainTokenTime($lobby);
        return $ticketInfo;
    }

    public function GetTokenID(int $lobby): int
    {
        switch ($lobby) {
            case 1:
                return ConfigGenerator::Instance()->PvP_B_TicketId_1;
            case 2:
                return ConfigGenerator::Instance()->PvP_B_TicketId_2;
        }
        return -1;
    }

    public function GetMaxTokens(int $lobby): int
    {
        switch ($lobby) {
            case 1:
                return ConfigGenerator::Instance()->PvP_B_MaxTickets_1;
            case 2:
                return ConfigGenerator::Instance()->PvP_B_MaxTickets_2;
        }
        return -1;
    }

    public function GetSceneID(int $lobby): int
    {
        switch ($lobby) {
            case 1:
                return $this->info->CoinScene;
            case 2:
                return $this->info->PTScene;
        }
        return -1;
    }

    public function GetPetaLimitLevel(int $lobby): int
    {
        switch ($lobby) {
            case 1:
                return ConfigGenerator::Instance()->PvP_B_PetaLvLimit_1;
            case 2:
                return 0;
        }
        return -1;
    }


    public function SetNextTokenTime(int $lobby): bool
    {
        //todo
        switch ($lobby) {
            case 1:
                return false;
            case 2:
                return false;
        }
        return false;
    }

    public function GetRemainTokenTime(int $lobby): int
    {
        //todo        
        switch ($lobby) {
            case 1:
                return 0;
            case 2:
                return 0;
        }
        return -1;
    }

    public function GetRank(int $lobby): stdclass
    {
        //todo
        $result = new stdclass;
        switch ($lobby) {
            case 1:
                $result->raceAmount = 12;
                $result->aveRank = "1.11";
                $result->rank = "5";
                break;
            case 2:
                $result->raceAmount = 34;
                $result->aveRank = "3.21";
                $result->rank = "15";
                break;
        }
        return $result;
    }
}