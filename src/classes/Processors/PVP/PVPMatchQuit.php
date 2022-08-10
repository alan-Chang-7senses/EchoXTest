<?php

namespace Processors\PVP;

use Consts\EnvVar;
use Consts\ErrorCode;
use Holders\ResultData;
use Games\Pools\UserPool;
use Accessors\PDOAccessor;
use Processors\Races\BaseRace;
use Games\PVP\RaceRoomsHandler;
use Games\Accessors\UserAccessor;
use Games\PVP\RaceRoomSeatHandler;
use Games\Exceptions\RaceException;

class PVPMatchQuit extends BaseRace
{

    protected bool|null $mustInRace = false;
    public function Process(): ResultData
    {
        
        if ($this->userInfo->room == 0) {
            throw new RaceException(RaceException::UserNotInMatch);
        }

        $raceRoomID = $this->userInfo->room;

        $accessor = new PDOAccessor(EnvVar::DBMain);
        $accessor->Transaction(function () use (&$raceRoomID) {
            $raceroomSeatHandler = new RaceRoomSeatHandler($raceRoomID);
            $raceroomSeatHandler->LeaveSeat();
            $seatUsers = $raceroomSeatHandler->GetSeatUsers();                      
            $raceroomHandler = new RaceRoomsHandler($this->userInfo->lobby);
            $raceroomHandler->UpdateUsers($raceRoomID, $seatUsers);
            
            $userAccessor = new UserAccessor();
            $userAccessor ->ModifyUserValuesByID($this->userInfo->id, ['Lobby' => 0, 'Room' => 0]);

        });
        UserPool::Instance()->delete($this->userInfo->id);
        // $userHandler = new UserHandler($this->userInfo->id);
        // $userHandler->SaveData(['lobby' => 0, 'room' => 0]);

        $result = new ResultData(ErrorCode::Success);
        return $result;
    }
}
