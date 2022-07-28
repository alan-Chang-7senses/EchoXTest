<?php

namespace Games\Races;

use Generators\ConfigGenerator;
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
            case 1: //1. 金幣晉級賽
                return ConfigGenerator::Instance()->PvP_B_NewRoomRate_1;
            case 2: //2. UCG晉級賽
                return ConfigGenerator::Instance()->PvP_B_NewRoomRate_2;
        }

        return 0;
    }

    public function GetMatchRoomID(int $lobby, int $lowBound, int $upBound): int
    {
        $rooms = $this->accessor->GetMatchRooms($lobby, $lowBound, $upBound);
        $newRoomRate = $this->GetNewRomRate($lobby);
        $roomNumber = count($rooms);
        $isAddNewroom = ($roomNumber > 0) ? (rand(1, 1000) < $newRoomRate) : true;

        if ($isAddNewroom) {
            $this->raceRoomID = $this->accessor->AddNewRoom($lobby, $lowBound, $upBound);

        }
        else {
            $rnd = rand(0, $roomNumber - 1);
            $this->raceRoomID = $rooms[$rnd]->RaceRoomID;
        }

        return $this->raceRoomID;
    }


    public function UpdateUsers(int $raceRoomID, array $users)
    {
        $seatCount = count($users);

        if ($seatCount >= ConfigGenerator::Instance()->AmountRacePlayerMax) {
            $bind['Status'] = 2;
        }

        $bind["RaceRoomSeats"] = json_encode($users);
        return $this->accessor->Update($raceRoomID, $bind);
    }


    public function StartRace(int $raceRoomID, $raceID)
    {
        $bind = [
            'Status' => 3,
            'RaceID' => $raceID,
        ];
        return $this->accessor->Update($raceRoomID, $bind);
    }

    public function GetTokenID(int $lobby)
    {
        return ($lobby == 1) ?ConfigGenerator::Instance()->PvP_B_TicketId_1 : ConfigGenerator::Instance()->PvP_B_TicketId_2;
    }

    public function FindItemAmount(int $userID, int $itemID): int
    {
        $bind = [
            'UserID' => $userID,
            'ItemID' => $itemID
        ];

        return $this->accessor->FindItemAmount($bind);
    }

}
