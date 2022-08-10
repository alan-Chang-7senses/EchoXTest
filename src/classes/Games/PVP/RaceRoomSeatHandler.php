<?php

namespace Games\PVP;

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


    public function TakeSeat(int $userID): int
    {
        $allSeats = $this->accessor->GetSeats($this->raceRoomID);
        if (count($allSeats) == 0) {
            $allSeats = $this->accessor->CreateSeats($this->raceRoomID, ConfigGenerator::Instance()->AmountRacePlayerMax);
        }

        $raceRoomSeatID = $this->FindSeatID($allSeats,  $userID);
        if($raceRoomSeatID != false)
        {
            //to do remove RaceRoomSeat tble
            //這裡也有問題不該有座位
            return $raceRoomSeatID;
        }

        $raceRoomSeatID = $this->FindSeatID($allSeats, 0);
        if ($raceRoomSeatID  == false)
        {
            //RaceRoomSeat 標記有問題
            throw new RaceException(RaceException::UserMatchError);
        }

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


    private function FindSeatID(array|int $allSeats, int $userID): int|false
    {
        if (!is_array($allSeats)) {
            $allSeats = $this->accessor->GetSeats($allSeats);
        }

        foreach ($allSeats as $seat) {
            if ($seat->UserID == $userID) {
                return $seat->RaceRoomSeatID;
            }
        }
        return false;
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
