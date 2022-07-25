<?php

namespace Processors\Races;

use Consts\EnvVar;
use Consts\ErrorCode;
use Holders\ResultData;
use Accessors\PDOAccessor;
use Games\Users\UserHandler;
use Processors\Races\BaseRace;
use Games\Races\RaceRoomsHandler;
use Games\Exceptions\RaceException;
use Games\Races\RaceRoomSeatHandler;

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
            $raceroomHandler = new RaceRoomsHandler();
            $raceroomHandler->UpdateUsers($raceRoomID, $seatUsers);
        });

        $userHandler = new UserHandler($this->userInfo->id);
        $userHandler->SaveData(['room' => 0]);

        $result = new ResultData(ErrorCode::Success);
        return $result;
    }
}
