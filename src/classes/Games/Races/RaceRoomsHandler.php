<?php

namespace Games\Races;

use Generators\ConfigGenerator;
use Games\Accessors\RaceRoomsAccessor;

class RaceRoomsHandler
{

    private RaceRoomsAccessor $raceRoomsAccessor;
    private int $lobby;

    private int $raceRoomID;

    public function __construct()
    {
        $this->raceRoomsAccessor = new RaceRoomsAccessor();
    }


    public function SetConfig(int $lobby){
        $this->lobby = $lobby;

        switch ($lobby) {
            case 1: //1. 金幣晉級賽
                $this->newRoomRate = ConfigGenerator::Instance()->PvP_B_NewRoomRate_1;
                break;
            case 2: //2. UCG晉級賽
                $this->newRoomRate = ConfigGenerator::Instance()->PvP_B_NewRoomRate_2;
        }
    }

    public function GetMatchRoomID(int $lowBound, int $upBound): int
    {


        $rooms = $this->raceRoomsAccessor->GetMatchRooms($this->lobby, $lowBound, $upBound);
        $roomNumber = count($rooms);
        $isAddNewroom = ($roomNumber > 0) ? (rand(1, 1000) < $this->newRoomRate) : true;

        if ($isAddNewroom) {
            $this->raceRoomID = $this->raceRoomsAccessor->AddNewRoom($this->lobby, $lowBound, $upBound);

        }
        else {
            $race = rand(0, $roomNumber - 1);
            $this->raceRoomID = $rooms[$race]->RaceRoomID;
        }


        return $this->raceRoomID;
    }


    public function UpdateUsers(array $users)
    {
        $seatCount = count($users);

        if ($seatCount >= ConfigGenerator::Instance()->AmountRacePlayerMax) {
            $bind['Status'] = 2;
        }

        $bind["RaceRoomSeats"] = json_encode($users);
        return $this->raceRoomsAccessor->Update($this->raceRoomID, $bind);
    }


    public function StartRace(int $raceRoomID, $raceID)
    {
        $bind = [
            'Status' => 3,
            'RaceID' => $raceID,
        ];
        return $this->raceRoomsAccessor->Update($raceRoomID, $bind);
    }

}
