<?php

namespace Games\PVP;

use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\Globals;
use Exception;
use Games\Accessors\QualifyingSeasonAccessor;
use Games\Consts\RaceValue;
use Games\Exceptions\RaceException;
use Games\Pools\ItemInfoPool;
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

    public array $NowSeasonIDArr;
    public array $infoArr;
    public array $recodeArr;

    private TicketInfoPool $ticketPool;
    private QualifyingSeasonAccessor $pdoAccessor;

    public function __construct() {
        $this->ticketPool = TicketInfoPool::Instance();
        $this->pdoAccessor = new QualifyingSeasonAccessor();
        $this->DetectQualifying();
    }

    private function DetectQualifying() {
        $this->NowSeasonIDArr = [];
        $this->infoArr = [];

        foreach (QualifyingHandler::Lobbies as $lobby) {

            // 輪巡大廳編號 查詢 QualifyingSeasonData 開放賽季資料
            $data = $this->pdoAccessor->GetOpenQualifyingSeasonData($lobby);

            if ($data !== false) {
                // 紀錄大廳開放賽季編號
                $this->NowSeasonIDArr[] = $data->SeasonID;

                // 賽季資料 整理至陣列
                $info = new stdClass;
                $info->ID = $data->ID;
                $info->SeasonID = $data->SeasonID;
                $info->Lobby = $data->Lobby;
                $info->Status = $data->Status;
                $info->Assign = $data->Assign;
                $info->UpdateTime = $data->UpdateTime;
                $this->infoArr[$data->Lobby] = $info;
            }
        }

        // 取得所有賽季的記分方式
        $this->recodeArr = $this->pdoAccessor->GetSeasonRecordType();
    }
    
    public function DetectSeason() {
        $qualifyingData = [];
        $defSeasonIDArr = [];
        $notInSeasonIDArr = [];

        // (QualifyingData) : 取得企劃定義的賽季大廳資料
        foreach (QualifyingHandler::Lobbies as $lobby) {
            $data = $this->pdoAccessor->GetOpenQualifyingDataByLobby($lobby);
            if ($data !== false) {
                $qualifyingData[$lobby] = $data;
                $defSeasonIDArr[$lobby] = $data->SeasonID;
                echo '[QualifyingData] new seasonID=' . $data->SeasonID . ',  Lobby=' . $data->Lobby . ',  startTime=' . $data->StartTime .'('.date('Y-m-d H:i:s',$data->StartTime).'),  endTime=' . $data->EndTime .'('.date('Y-m-d H:i:s',$data->EndTime).')'. PHP_EOL;
            }
        }
        

        // (QualifyingSeasonData) $this->NowSeasonIDArr : 目前遊戲紀錄 進行中賽季資料
        if (count($this->NowSeasonIDArr) > 0) {
            // 若逾期，則將 Status 改成 0
            $notInSeasonIDArr = array_diff($this->NowSeasonIDArr, $defSeasonIDArr);
            $this->pdoAccessor->ModifyQualifyingSeasonDataStatus($notInSeasonIDArr);
            
            foreach ($notInSeasonIDArr as $notInSeasonID) {
                echo '[QualifyingSeasonData] remove seasonID (status = 0) : '.$notInSeasonID.PHP_EOL;
            }
        }
        
        // (QualifyingSeasonData) 新增資料
        if (count($qualifyingData) > 0) {
            foreach ($qualifyingData as $data) {
                // 不在 目前遊戲紀錄進行中賽季資料 
                if (in_array($data->SeasonID, $this->NowSeasonIDArr) == false) {
                    $this->pdoAccessor->AddQualifyingSeasonData($data->SeasonID, $data->Lobby);
                    echo '[QualifyingSeasonData] add seasonID=' . $data->SeasonID . ',  Lobby=' . $data->Lobby . ',  startTime=' . $data->StartTime .'('.date('Y-m-d H:i:s',$data->StartTime).'),  endTime=' . $data->EndTime .'('.date('Y-m-d H:i:s',$data->EndTime).')'. PHP_EOL;
                }
            }
        }

        $this->DetectQualifying();
    }

    public function CheckLobbyID(int $lobby) {
        if (in_array($lobby, QualifyingHandler::Lobbies) == false) {
            throw new Exception('QualifyingHandler lobby ' . $lobby . ' is invalid', ErrorCode::ParamError);
        }
    }

    public function CheckAnySeasonIsExist() {
        if (count($this->NowSeasonIDArr) <= 0) {
            throw new RaceException(RaceException::NoSeasonData);
        }
    }

    public function GetSeasonRemaintime(int $lobby): int {
        $qualifyData = $this->pdoAccessor->GetOpenQualifyingDataByLobby($lobby);
        if ($qualifyData !== false) {
            $remainTime = $qualifyData->EndTime - $GLOBALS[Globals::TIME_BEGIN] - ConfigGenerator::Instance()->PvP_B_StopMatch;
            if ($remainTime < 0)
                $remainTime = 0;
        }
        else {
            $remainTime = 0;
        }
        
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
        $qualifyData = $this->pdoAccessor->GetOpenQualifyingDataByLobby($lobby);
        if ($qualifyData !== false) {
            return $qualifyData->Scene;
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
        if (array_key_exists($lobby, $this->infoArr) == true) {
            return $this->infoArr[$lobby]->SeasonID;
        }
        return RaceValue::NOSeasonID;
    }

    public function GetRecordTypeBySeasonID(int $seasonID): int {
        if (array_key_exists($seasonID, $this->recodeArr) == true) {
            return $this->recodeArr[$seasonID];
        }
        return 0;
    }
}
