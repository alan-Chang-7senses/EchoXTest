<?php

namespace Games\Races;

use Consts\Sessions;
use Generators\ConfigGenerator;
use Games\Exceptions\RaceException;
use Games\Accessors\RaceRoomSeatAccessor;

class RaceRoomSeatHandler
{

    private RaceRoomSeatAccessor $raceRoomSeatAccessor;
    private int $raceRoomID;

    public function __construct(int $raceRoomID)
    {
        $this->raceRoomSeatAccessor = new RaceRoomSeatAccessor();
        $this->raceRoomID = $raceRoomID;
    }


    public function TakeSeat(array &$seatUserIDs): int
    {
        $raceRoomSeatID = -1;

        $roomSeats = $this->raceRoomSeatAccessor->GetSeats($this->raceRoomID, ConfigGenerator::Instance()->AmountRacePlayerMax);

        $raceRoomSeatID = -1;
        foreach ($roomSeats as $roomSeat) {
            if ($roomSeat->UserID == 0) {
                if ($raceRoomSeatID == -1) {
                    $raceRoomSeatID = $roomSeat->RaceRoomSeatID;
                }
            }
            else {
                $seatUserIDs[$roomSeat->UserID] = $roomSeat->RaceRoomSeatID;
            }
        }

        if ($raceRoomSeatID == -1) {
            //RaceRoom 標記有問題
            throw new RaceException(RaceException::UserMatchError);
        }

        $seatUserIDs[$_SESSION[Sessions::UserID]] = $raceRoomSeatID;

        $bind = [
            'UserID' => $_SESSION[Sessions::UserID]
        ];

        $this->raceRoomSeatAccessor->Update($raceRoomSeatID, $bind);

        return $raceRoomSeatID;
    }


    public function LeaveSeat(int $raceRoomSeatID)
    {
        $bind = [
            'UserID' => 0
        ];
        return $this->raceRoomSeatAccessor->Update($raceRoomSeatID, $bind);
    }
}
