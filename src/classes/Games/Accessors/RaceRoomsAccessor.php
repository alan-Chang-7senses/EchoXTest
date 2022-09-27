<?php

namespace Games\Accessors;

use Accessors\PDOAccessor;
use Consts\Globals;
use stdClass;

class RaceRoomsAccessor extends BaseAccessor {

    private function useTable(): PDOAccessor {
        return $this->MainAccessor()->FromTable('RaceRooms');
    }

    public function GetRoom(int $raceRoomID): stdClass|false {
        return $this->useTable()->WhereEqual('RaceRoomID', $raceRoomID)->ForUpdate()->Fetch();
    }

    public function GetMatchRooms(int $lobby, string $version, int $lowBound, int $upBound): array {
        return $this->useTable()->WhereEqual('Status', 1)->
                        WhereEqual('Lobby', $lobby)->WhereEqual('Version', $version)->WhereEqual('LowBound', $lowBound)->WhereEqual('UpBound', $upBound)->
                        ForUpdate()->FetchAll();
    }

    public function GetIdleRoom(int $lobby, string $version, int $lowBound, int $upBound): stdClass|false {
        return $this->useTable()->WhereEqual('Status', 0)->
                        WhereEqual('Lobby', $lobby)->WhereEqual('Version', $version)->WhereEqual('LowBound', $lowBound)->WhereEqual('UpBound', $upBound)->
                        ForUpdate()->Fetch();
    }

    public function AddNewRoom(int $lobby, string $version, int $lowBound, int $upBound): stdClass|false {
        $this->useTable()->Add([
            'Status' => 1,
            'Lobby' => $lobby,
            'Version' => $version,            
            'LowBound' => $lowBound,
            'UpBound' => $upBound,
            'CreateTime' => $GLOBALS[Globals::TIME_BEGIN],
            'UpdateTime' => $GLOBALS[Globals::TIME_BEGIN],
        ]);

        $raceRommID = (int) $this->useTable()->LastInsertID();
        return $this->useTable()->WhereEqual('RaceroomID', $raceRommID)->ForUpdate()->Fetch();
    }

    public function Update(int $raceRoomID, array $bind): bool {
        $bind['UpdateTime'] = $GLOBALS[Globals::TIME_BEGIN];

        return $this->useTable()->WhereEqual('RaceRoomID', $raceRoomID)->Modify($bind);
    }

}
