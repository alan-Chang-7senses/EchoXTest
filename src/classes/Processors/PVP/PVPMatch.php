<?php

namespace Processors\PVP;

use Consts\EnvVar;
use Consts\ErrorCode;
use Holders\ResultData;
use Helpers\InputHelper;
use Accessors\PDOAccessor;
use Games\Users\UserHandler;
use Processors\Races\BaseRace;
use Generators\ConfigGenerator;
use Games\Races\RaceRoomsHandler;
use Games\Exceptions\RaceException;
use Games\Races\RaceRoomSeatHandler;

class PVPMatch extends BaseRace
{

    protected bool|null $mustInRace = false;
    public function Process(): ResultData
    {
        $lobby = InputHelper::post('lobby');

        if (($lobby == 0) || ($lobby > 2)) {
            throw new RaceException(RaceException::UserMatchError);
        }

        if ($this->userInfo->room != 0) {
            throw new RaceException(RaceException::UserInMatch);
        }

        $raceRoomID = 0;

        $accessor = new PDOAccessor(EnvVar::DBMain);
        $accessor->Transaction(function () use ($lobby, &$raceRoomID) {

            $raceroomHandler = new RaceRoomsHandler();
            //todo
            $lowbound = 0;
            $upbound = 0;
            //
            $raceRoomID = $raceroomHandler->GetMatchRoomID($lobby, $lowbound, $upbound);
            $raceroomSeatHandler = new RaceRoomSeatHandler($raceRoomID);
            $raceroomSeatHandler->TakeSeat();
            $seatUserIDs = $raceroomSeatHandler->GetSeatUsers();     
            $raceroomHandler->UpdateUsers($raceRoomID, $seatUserIDs);
        });

        $userHandler = new UserHandler($this->userInfo->id);
        $userHandler->SaveData(['lobby' => $lobby,'room' => $raceRoomID]);

        $result = new ResultData(ErrorCode::Success);
        $result->raceRoomID = $raceRoomID;
        $result->extraMatchSeconds = ConfigGenerator::Instance()->PvP_ExtraMatchSeconds;
        $result->maxMatchSeconds = ConfigGenerator::Instance()->PvP_MaxMatchSeconds;

        return $result;
    }
}
