<?php

namespace Processors\PVP;

use Consts\EnvVar;
use Consts\ErrorCode;
use Holders\ResultData;
use Helpers\InputHelper;
use Accessors\PDOAccessor;
use Games\Users\UserHandler;
use Processors\Races\BaseRace;
use Games\PVP\RaceRoomsHandler;
use Generators\ConfigGenerator;
use Games\PVP\QualifyingHandler;
use Games\PVP\RaceRoomSeatHandler;
use Games\Exceptions\RaceException;

class PVPMatch extends BaseRace
{

    protected bool|null $mustInRace = false;
    public function Process(): ResultData
    {
        $lobby = InputHelper::post('lobby');       

        if ($this->userInfo->room != 0) {
            throw new RaceException(RaceException::UserInMatch);
        }       
        
        $qualifyingHandler = new QualifyingHandler();
        $qualifyingHandler->CheckLobbyID($lobby);
        if ($qualifyingHandler->NowSeasonID == -1) {
            throw new RaceException(RaceException::NoSeasonData);
        }

        $useTicketId = $qualifyingHandler->GetTicketID($lobby);
        if ($qualifyingHandler->FindItemAmount($this->userInfo->id, $useTicketId) <= 0) {
            throw new RaceException(RaceException::UserTicketNotEnough);
        }

        $raceRoomID = 0;
        $accessor = new PDOAccessor(EnvVar::DBMain);
        $accessor->Transaction(function () use ($lobby, &$raceRoomID, $qualifyingHandler) {

            //todo
            $lowbound = 0;
            $upbound = 0;
            //
            $raceroomHandler = new RaceRoomsHandler($lobby);            
            $raceRoomID = $raceroomHandler->GetMatchRoomID($lowbound, $upbound, $qualifyingHandler->NowSeasonID);
            $raceroomSeatHandler = new RaceRoomSeatHandler($raceRoomID);
            $raceroomSeatHandler->TakeSeat();
            $seatUserIDs = $raceroomSeatHandler->GetSeatUsers();
            $raceroomHandler->UpdateUsers($raceRoomID, $seatUserIDs);
        });

        $userHandler = new UserHandler($this->userInfo->id);
        $userHandler->SaveData(['lobby' => $lobby, 'room' => $raceRoomID]);

        $result = new ResultData(ErrorCode::Success);
        $result->raceRoomID = $raceRoomID;
        $result->extraMatchSeconds = ConfigGenerator::Instance()->PvP_ExtraMatchSeconds;
        $result->maxMatchSeconds = ConfigGenerator::Instance()->PvP_MaxMatchSeconds;

        return $result;
    }
}
