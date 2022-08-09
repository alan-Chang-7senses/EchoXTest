<?php

namespace Games\Accessors;

use Consts\Globals;
use Accessors\PDOAccessor;

class RaceRoomsAccessor extends BaseAccessor
{

    private function useTable(): PDOAccessor
    {
        return $this->MainAccessor()->FromTable('RaceRooms');
    }

    public function GetMatchRooms(int $lobby, int $lowBound, int $upBound, $qualifyingSeasonID): array
    {
        return $this->useTable()->WhereEqual('Status', 1)->WhereEqual('Lobby', $lobby)
            ->WhereEqual('LowBound', $lowBound)->WhereEqual('UpBound', $upBound)->WhereEqual('QualifyingSeasonID', $qualifyingSeasonID)
            ->ForUpdate()->FetchAll();
    }

    public function GetIdleRooms(int $lobby, int $lowBound, int $upBound, $qualifyingSeasonID): array
    {
        return $this->useTable()->WhereEqual('Status', 0)->WhereEqual('Lobby', $lobby)
            ->WhereEqual('LowBound', $lowBound)->WhereEqual('UpBound', $upBound)->WhereEqual('QualifyingSeasonID', $qualifyingSeasonID)
            ->ForUpdate()->FetchAll();
    }

    public function AddNewRoom(int $lobby, int $lowBound, int $upBound,int $qualifyingSeasonID): int
    {
        $this->useTable()->Add([
            'Status' => 1,
            'Lobby' => $lobby,
            'LowBound' => $lowBound,
            'UpBound' => $upBound,
            'QualifyingSeasonID' => $qualifyingSeasonID,
            'CreateTime' => $GLOBALS[Globals::TIME_BEGIN],
            'UpdateTime' => $GLOBALS[Globals::TIME_BEGIN],
            'RaceID' => 0,
            'RaceRoomSeats' => ""
        ]);

        $raceRommID = (int)$this->useTable()->LastInsertID();
        $this->useTable()->WhereEqual('RaceroomID', $raceRommID)->ForUpdate()->Fetch();
        return $raceRommID;
    }

    public function Update(int $raceRoomID, array $bind): bool
    {
        $bind['UpdateTime'] = $GLOBALS[Globals::TIME_BEGIN];

        return $this->useTable()->WhereEqual('RaceRoomID', $raceRoomID)->Modify($bind);
    }


}
