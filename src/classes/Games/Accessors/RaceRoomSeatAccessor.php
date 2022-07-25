<?php

namespace Games\Accessors;

use Consts\Globals;
use Accessors\PDOAccessor;

class RaceRoomSeatAccessor extends BaseAccessor
{
    private function useTable(): PDOAccessor
    {
        return $this->MainAccessor()->FromTable('RaceRoomSeat');
    }

    public function GetSeats(int $raceRoomID): array    
    {
        return $this->useTable()->WhereEqual('RaceRoomID', $raceRoomID)->ForUpdate()->FetchAll();
    }

    public function CreateSeats(int $raceRoomID, int $maxseats)
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

        return $this->GetSeats($raceRoomID);
    }

    public function Update(int $raceRoomSeatID, array $bind): bool
    {
        $bind['UpdateTime'] = $GLOBALS[Globals::TIME_BEGIN];

        return $this->useTable()->WhereEqual('RaceRoomSeatID', $raceRoomSeatID)->Modify($bind);
    }
}
