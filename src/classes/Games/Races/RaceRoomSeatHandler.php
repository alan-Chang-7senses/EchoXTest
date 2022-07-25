<?php

namespace Games\Races;

use Consts\Sessions;
use Generators\ConfigGenerator;
use Games\Exceptions\RaceException;
use Games\Accessors\RaceRoomSeatAccessor;

class RaceRoomSeatHandler
{

    private RaceRoomSeatAccessor $accessor;
    private int $raceRoomID;
    private int $userID;

    public function __construct(int $raceRoomID)
    {
        $this->accessor = new RaceRoomSeatAccessor();
        $this->raceRoomID = $raceRoomID;
        $this->userID = $_SESSION[Sessions::UserID];
    }


    public function TakeSeat(): int
    {
        $allSeats = $this->accessor->GetSeats($this->raceRoomID);
        if (count($allSeats) == 0) {
            $allSeats = $this->accessor->CreateSeats($this->raceRoomID, ConfigGenerator::Instance()->AmountRacePlayerMax);
        }

        $raceRoomSeatID = $this->FindSeatID($allSeats, 0);

        $bind = [
            'UserID' => $this->userID
        ];

        $this->accessor->Update($raceRoomSeatID, $bind);

        return $raceRoomSeatID;
    }

    public function LeaveSeat()
    {
        $raceRoomSeatID = $this->FindSeatID($this->raceRoomID, $this->userID);
        $bind = [
            'UserID' => 0
        ];
        return $this->accessor->Update($raceRoomSeatID, $bind);
    }


    private function FindSeatID(array|int $allSeats, int $userID): int
    {
        if (!is_array($allSeats)) {
            $allSeats = $this->accessor->GetSeats($allSeats);
        }

        foreach ($allSeats as $seat) {
            if ($seat->UserID == $userID) {
                return $seat->RaceRoomSeatID;
            }
        }

        //RaceRoom 標記有問題
        throw new RaceException(RaceException::UserMatchError);
    }

    public function GetSeatUsers(): array
    {
        $allSeats = $this->accessor->GetSeats($this->raceRoomID);
        $result = array();
        foreach ($allSeats as $seat) {
            if ($seat->UserID != 0) {
                $result[$seat->UserID] = $seat->RaceRoomSeatID;
            }
        }
        return $result;
    }


}
