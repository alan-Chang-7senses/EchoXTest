<?php

namespace Games\PVP;

use Generators\ConfigGenerator;
use Games\Accessors\RaceRoomsAccessor;

class RaceRoomsHandler
{

    private RaceRoomsAccessor $accessor;

    private int $lobby;
    public function __construct(int $lobby)
    {
        $this->accessor = new RaceRoomsAccessor();
        $this->lobby = $lobby;
    }

    private function GetNewRomRate(): int
    {
        switch ($this->lobby) {
            case 1: //1. 金幣晉級賽
                return ConfigGenerator::Instance()->PvP_B_NewRoomRate_1;
            case 2: //2. UCG晉級賽
                return ConfigGenerator::Instance()->PvP_B_NewRoomRate_2;
        }
        return 0;
    }

    public function GetMatchRoomID(int $lowBound, int $upBound, $qualifyingSeasonID): int
    {
        $rooms = $this->accessor->GetMatchRooms($this->lobby, $lowBound, $upBound, $qualifyingSeasonID);
        $roomNumber = count($rooms);
        $isAddNewroom = ($roomNumber > 0) ? (rand(1, 1000) < $this->GetNewRomRate()) : true;

        if ($isAddNewroom) {
            $this->raceRoomID = $this->accessor->AddNewRoom($this->lobby, $lowBound, $upBound, $qualifyingSeasonID);
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


    public static function StartRace(int $raceRoomID, $raceID)
    {
        $bind = [
            'Status' => 3,
            'RaceID' => $raceID,
        ];

        $accessor = new RaceRoomsAccessor();
        return $accessor->Update($raceRoomID, $bind);
    }


}
