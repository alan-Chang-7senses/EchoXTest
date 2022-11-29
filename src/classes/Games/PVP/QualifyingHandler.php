<?php

namespace Games\PVP;

use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\Globals;
use DateTime;
use Exception;
use Games\Accessors\QualifyingSeasonAccessor;
use Games\Consts\RaceValue;
use Games\Exceptions\RaceException;
use Games\Pools\ItemInfoPool;
use Games\Pools\QualifyingSeasonPool;
use Games\Pools\TicketInfoPool;
use Games\PVP\Holders\TicketInfoHolder;
use Games\Races\RaceUtility;
use Games\Users\UserBagHandler;
use Generators\ConfigGenerator;
use Generators\DataGenerator;
use stdClass;

class QualifyingHandler {

    public const Lobbies = [RaceValue::LobbyCoinA, RaceValue::LobbyCoinB,
        RaceValue::LobbyPetaTokenA, RaceValue::LobbyPetaTokenB,
        RaceValue::LobbyStudy];
    public const MatchLobbies = [RaceValue::LobbyCoinA, RaceValue::LobbyCoinB,
        RaceValue::LobbyPetaTokenA, RaceValue::LobbyPetaTokenB];

    public int $NowSeasonID;
    public array $NowSeasonIDArr;

    private QualifyingSeasonPool $pool;
    private TicketInfoPool $ticketPool;
    private QualifyingSeasonAccessor $pdoAccessor;
    public stdClass $info;
    public array $infoArr;

    public function __construct() {
        $this->pool = QualifyingSeasonPool::Instance();
        $this->ticketPool = TicketInfoPool::Instance();
        $this->pdoAccessor = new QualifyingSeasonAccessor();
        $this->DetectCurrent();
    }

    private function DetectCurrent() {

        // 從 Pool 中取得 QualifyingSeasonData 資料表
        $result = $this->pool-> {"Current"};

        $this->NowSeasonIDArr = [];
        $this->infoArr = [];

        if ($result != false) {
            // 有資料
            foreach ($result as $data) {
                // 紀錄目前各大廳開放的賽季編號
                $this->NowSeasonIDArr[] = $data->SeasonID;

                // 賽季資料整理至陣列
                $info = new stdClass;
                $info->ID = $data->ID;
                $info->SeasonID = $data->SeasonID;
                $info->Lobby = $data->Lobby;
                $info->Status = $data->Status;
                $info->Assign = $data->Assign;
                $info->UpdateTime = $data->UpdateTime;
                $this->infoArr[] = $info;
            }
        }
        else {
            // 無資料
            $this->NowSeasonIDArr = [];
            $this->infoArr = [];
        }
    }
    
    public function DetectSeason() {

        // 從 koa_static (QualifyingData) 取得企劃定義的賽季大廳資料
        $qualifyingData = $this->pdoAccessor->GetQualifyingData();
        $defSeasonIDArr = [];
        foreach ($qualifyingData as $data) {
            $defSeasonIDArr[] = $data->SeasonID;
        }

        // 從 koa_main (QualifyingSeasonData) 取得遊戲中所有賽季資料
        if (count($this->NowSeasonIDArr) > 0) {

            // Pool 已存在 QualifyingSeasonData 資料
            foreach ($this->NowSeasonIDArr as $nowSeasonID) {
                if (in_array($nowSeasonID, $defSeasonIDArr) == false) {
                    // 若無在賽季列表中，則將 Status 改成 0
                    $bind = ["Status" => 0];
                    $this->pdoAccessor->ModifyQualifyingSeasonData($nowSeasonID, $bind);
                    array_diff($this->NowSeasonIDArr, array($nowSeasonID));
                }
            }
        }
        
        // 新資料
        if ($qualifyingData != false) {
            foreach ($qualifyingData as $data) {
                if (in_array($data->SeasonID, $this->NowSeasonIDArr) == false) {
                    $this->pdoAccessor->AddQualifyingSeasonData($data->SeasonID, $data->Lobby);
                }
            }
        }

        $this->pool->Delete("Current");
        $this->DetectCurrent();
    }

    public function CheckLobbyID(int $lobby) {
        if (in_array($lobby, QualifyingHandler::Lobbies) == false) {
            throw new Exception('QualifyingHandler lobby ' . $lobby . ' is invalid', ErrorCode::ParamError);
        }
    }

    public function CheckSeasonIsExist() {
        if (count($this->NowSeasonIDArr) <= 0) {
            throw new RaceException(RaceException::NoSeasonData);
        }
    }

    public function GetSeasonRemaintime(): int {
        $remainTime = $this->info->EndTime - $GLOBALS[Globals::TIME_BEGIN] - ConfigGenerator::Instance()->PvP_B_StopMatch;
        if ($remainTime < 0)
            $remainTime = 0;
        return (int) $remainTime;
    }

    public function GetTicketInfo(int $userID, int $lobby): TicketInfoHolder {
        $userBagHandler = new UserBagHandler($userID);

        $this->CheckLobbyID($lobby);
        $ticketInfo = new TicketInfoHolder();
        $ticketInfo->lobby = $lobby;
        $itemInfo = ItemInfoPool::Instance()->{ RaceUtility::GetTicketID($lobby)};
        $ticketInfo->ticketID = $itemInfo->ItemID;
        $ticketInfo->ticketName = $itemInfo->ItemName;
        $ticketInfo->ticketIcon = $itemInfo->Icon;
        $ticketInfo->amount = $userBagHandler->GetItemAmount($ticketInfo->ticketID);
        $ticketInfo->maxReceive = RaceUtility::GetMaxTickets($lobby);
        $ticketInfo->receiveCount = RaceUtility::GetTicketCount($lobby);
        $ticketInfo->receiveRemainTime = $this->GetRemainTicketTime($userID, $lobby);
        return $ticketInfo;
    }

    public function GetSceneID(int $lobby, int $defaultSceneID): int {
        switch ($lobby) {
            case RaceValue::LobbyCoinA:
            case RaceValue::LobbyCoinB:
                return $this->info->CoinScene;
            case RaceValue::LobbyPetaTokenA:
            case RaceValue::LobbyPetaTokenB:
                return $this->info->PTScene;
        }
        return $defaultSceneID;
    }

    public function GetPetaLimitLevel(int $lobby): int {
        switch ($lobby) {
            case RaceValue::LobbyCoinA:
            case RaceValue::LobbyCoinB:
                return ConfigGenerator::Instance()->PvP_B_PetaLvLimit_1;
        }
        return 0;
    }

    public function SetNextTokenTime(int $userID, int $lobby): bool {
        $keyValue = "";
        $updateColumn = "";
        switch ($lobby) {
            case RaceValue::LobbyCoinA:
            case RaceValue::LobbyCoinB:
                $keyValue = 'Ticket_Coin';
                $updateColumn = "CoinTime";
                break;
            case RaceValue::LobbyPetaTokenA:
            case RaceValue::LobbyPetaTokenB:
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
        } else {
            return false;
        }
    }

    public function GetRemainTicketTime(int $userID, int $lobby): int {
        $userRewardTimes = $this->ticketPool->{ $userID};

        if ($userRewardTimes != false) {
            $resultTime = 0;
            $nowtime = (int) $GLOBALS[Globals::TIME_BEGIN];
            switch ($lobby) {
                case RaceValue::LobbyCoinA:
                case RaceValue::LobbyCoinB:                    
                    $resultTime = $userRewardTimes->CoinTime - $nowtime;
                    break;
                case RaceValue::LobbyPetaTokenA:
                case RaceValue::LobbyPetaTokenB:
                    $resultTime = $userRewardTimes->PTTime - $nowtime;
                    break;
            }
            if ($resultTime < 0) {
                $resultTime = 0;
            }
            return $resultTime;
        } else {
            return 0;
        }
    }

    public function GetSeasonIDByLobby(int $lobby): int {

        foreach ($this->infoArr as $data) {
            if ($data->Lobby == $lobby) {
                return $data->SeasonID;
            }
        }

        return 0;
    }

}
