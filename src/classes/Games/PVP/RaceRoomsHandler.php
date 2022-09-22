<?php

namespace Games\PVP;

use Games\Accessors\RaceRoomsAccessor;
use Games\Consts\RaceValue;
use Games\Exceptions\RaceException;
use Generators\ConfigGenerator;
use stdClass;

class RaceRoomsHandler {

    private RaceRoomsAccessor $accessor;

    public function __construct() {
        $this->accessor = new RaceRoomsAccessor();
    }

    private function GetNewRomRate(int $lobby): int {
        switch ($lobby) {
            case RaceValue::LobbyCoin:
            case RaceValue::LobbyCoinB:                
                return ConfigGenerator::Instance()->PvP_B_NewRoomRate_1;
            case RaceValue::LobbyPT:
            case RaceValue::LobbyPetaTokenB:                
                return ConfigGenerator::Instance()->PvP_B_NewRoomRate_2;
            case RaceValue::LobbyStudy:
                return 0;//find match room first
        }
        return 1000;//find idle/new room
    }

    public function GetMatchRoom(int $lobby, int $lowBound, int $upBound): stdclass|false {

        if (rand(1, 1000) < $this->GetNewRomRate($lobby)) {//Get idle room
            return $this->GetIdleRoom($lobby, $lowBound, $upBound);
        } else {
            $rooms = $this->accessor->GetMatchRooms($lobby, $lowBound, $upBound);
            $roomNumber = count($rooms);
            if ($roomNumber > 0) {
                $rnd = rand(0, $roomNumber - 1);
                return $rooms[$rnd];
            } else {
                return $this->GetIdleRoom($lobby, $lowBound, $upBound);
            }
        }
    }

    public function GetIdleRoom(int $lobby, int $lowBound, int $upBound): stdclass|false {
        $idleRoom = $this->accessor->GetIdleRoom($lobby, $lowBound, $upBound);
        if ($idleRoom !== false) {
            return $idleRoom;
        } else {
            return $this->accessor->AddNewRoom($lobby, $lowBound, $upBound);
        }
    }

    public function GetRoomInfo(int $raceRoomID): stdClass|false {

        return $this->accessor->GetRoom($raceRoomID);
    }

    public function UpdateUsers(int $raceRoomID, array $users): bool {
        $seatCount = count($users);

        if ($seatCount >= ConfigGenerator::Instance()->AmountRacePlayerMax) {
            $bind['Status'] = RaceValue::RoomFull;
        } else if ($seatCount == 0) {
            $bind['Status'] = RaceValue::RoomIdle;
        } else {
            $bind['Status'] = RaceValue::RoomMatching;
        }

        $bind["RaceRoomSeats"] = json_encode($users);
        return $this->accessor->Update($raceRoomID, $bind);
    }

    public static function StartRace(int $raceRoomID, $raceID) {
        $bind = [
            'Status' => RaceValue::RoomClose,
            'RaceID' => $raceID,
        ];

        $accessor = new RaceRoomsAccessor();
        return $accessor->Update($raceRoomID, $bind);
    }

    public function JoinRoom(int $userID, stdClass $roomInfo): bool {
        if ($roomInfo->Status === RaceValue::RoomClose) {
            throw new RaceException(RaceException::UserMatchError);
        }

        $users = json_decode($roomInfo->RaceRoomSeats);
        $seatCount = count($users);
        if ($seatCount >= ConfigGenerator::Instance()->AmountRacePlayerMax) {
            throw new RaceException(RaceException::UserMatchError);
        }

        $key = array_search($userID, $users);
        if ($key !== false) {
            throw new RaceException(RaceException::UserInRoom);
        }
        $users[] = $userID;
        return $this->UpdateUsers($roomInfo->RaceRoomID, $users);
    }

    public function LeaveRoom(int $userID, int $raceRoomID): bool {
        $roomInfo = $this->accessor->GetRoom($raceRoomID);
        if ($roomInfo == false) {
            throw new RaceException(RaceException::UserMatchError);
        }
        if ($roomInfo->Status !== RaceValue::RoomClose) {
            $users = json_decode($roomInfo->RaceRoomSeats);
            $key = array_search($userID, $users);
            if ($key !== false) {
                unset($users[$key]);
                return $this->UpdateUsers($raceRoomID, array_values($users));
            } else {
                throw new RaceException(RaceException::UserNotInRoom);
            }
        } else {
            //房間已經關閉了不處理, 但需更新使用者狀態            
            return true;
        }
    }

}
