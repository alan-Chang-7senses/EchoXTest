<?php

namespace Games\PVP;

use stdClass;
use Games\Consts\RaceValue;
use Generators\ConfigGenerator;
use Games\Exceptions\RaceException;
use Games\Accessors\RaceRoomsAccessor;

class RaceRoomsHandler
{
    private RaceRoomsAccessor $accessor;

    public function __construct()
    {
        $this->accessor = new RaceRoomsAccessor();
    }

    private function GetNewRomRate(int $lobby): int
    {
        switch ($lobby) {
            case RaceValue::LobbyCoin:
                return ConfigGenerator::Instance()->PvP_B_NewRoomRate_1;
            case RaceValue::LobbyPT:
                return ConfigGenerator::Instance()->PvP_B_NewRoomRate_2;
        }
        return 0;
    }

    public function GetMatchRoom(int $lobby, int $lowBound, int $upBound, $qualifyingSeasonID): stdclass|false
    {
        $rooms = $this->accessor->GetMatchRooms($lobby, $lowBound, $upBound, $qualifyingSeasonID);
        $roomNumber = count($rooms);

        $isAddNewroom = ($roomNumber > 0) ? (rand(1, 1000) < $this->GetNewRomRate($lobby)) : true;
        if ($isAddNewroom) {
            $idleRooms = $this->accessor->GetIdleRooms($lobby, $lowBound, $upBound, $qualifyingSeasonID);

            if (count($idleRooms) > 0) {
                return $idleRooms[0];
            }
            else {
                return $this->accessor->AddNewRoom($lobby, $lowBound, $upBound, $qualifyingSeasonID);
            }
        }
        else {
            $rnd = rand(0, $roomNumber - 1);
            return $rooms[$rnd];
        }
    }


    public function UpdateUsers(int $raceRoomID, array $users): bool
    {
        $seatCount = count($users);

        if ($seatCount >= ConfigGenerator::Instance()->AmountRacePlayerMax) {
            $bind['Status'] = 2;
        }
        else if ($seatCount == 0) {
            $bind['Status'] = 0;
        }
        else {
            $bind['Status'] = 1;
        }

        $bind["RaceRoomSeats"] = json_encode($users);
        return $this->accessor->Update($raceRoomID, $bind);
    }

    public static function StartRace(int $raceRoomID, $raceID)
    {
        $bind = [
            'Status' => 3,
            'RaceID' => $raceID,
        ];

        $accessor = new RaceRoomsAccessor();
        return $accessor->Update($raceRoomID, $bind);
    }

    public function TakeSeat(int $userID, stdClass $roomInfo): bool
    {
        $users = json_decode($roomInfo->RaceRoomSeats, true);
        $seatCount = count($users);

        if (($seatCount >= ConfigGenerator::Instance()->AmountRacePlayerMax) ||
        (isset($users[$userID]))) {
            throw new RaceException(RaceException::UserMatchError);
        }
        $users[$userID] = $seatCount;
        return $this->UpdateUsers($roomInfo->RaceRoomID, $users);
    }

    public function LeaveSeat(int $userID, int $raceRoomID): bool
    {
        $roomInfo = $this->accessor->GetRoom($raceRoomID);
        if ($roomInfo == false) {
            throw new RaceException(RaceException::UserMatchError);
        }

        $users = json_decode($roomInfo->RaceRoomSeats, true);
        if (isset($users[$userID]) == false) {
            throw new RaceException(RaceException::UserMatchError);
        }

        unset($users[$userID]);
        return $this->UpdateUsers($raceRoomID, $users);
    }
}