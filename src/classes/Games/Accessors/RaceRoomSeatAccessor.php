<?php

namespace Games\Accessors;

use Consts\Globals;
use Accessors\PDOAccessor;

class RaceRoomSeatAccessor extends BaseAccessor
{

    private $allSeats;

    public function useTable(): PDOAccessor
    {

        return $this->MainAccessor()->FromTable('RaceRoomSeat');
    }

    public function GetSeats(int $raceRoomID, int $maxseats): array
    {
        $this->allSeats = $this->useTable()->WhereEqual('RaceRoomID', $raceRoomID)->ForUpdate()->FetchAll();

        if  (count( $this->allSeats ) == 0)
        {
            $this->CreateSeats( $raceRoomID, $maxseats);
        }

        return $this->allSeats;
    }

    private function CreateSeats(int $raceRoomID, int $maxseats)
    {

        for ($i = 1; $i <= $maxseats; ++$i) {
            $this->useTable()->Add([
                'RaceRoomID' => $raceRoomID,
                'Seat' => $i,
                'UserID' => 0,
                'CreateTime' => $GLOBALS[Globals::TIME_BEGIN],
                'UpdateTime' => $GLOBALS[Globals::TIME_BEGIN],
            ]);
        }

        $this->allSeats = $this->useTable()->WhereEqual('RaceRoomID', $raceRoomID)->ForUpdate()->FetchAll();
    }


    public function Update(int $raceRoomSeatID, array $bind): bool
    {
        $bind['UpdateTime'] = $GLOBALS[Globals::TIME_BEGIN];

        return $this->useTable()->WhereEqual('RaceRoomSeatID', $raceRoomSeatID)->Modify($bind);
    }
}
