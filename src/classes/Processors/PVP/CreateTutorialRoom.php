<?php

namespace Processors\PVP;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\Globals;
use Consts\Sessions;
use Games\Consts\RaceValue;
use Games\Exceptions\RaceException;
use Games\Pools\UserPool;
use Games\PVP\QualifyingHandler;
use Games\PVP\RaceRoomsHandler;
use Games\Users\UserBagHandler;
use Games\Users\UserHandler;
use Games\Races\RaceUtility;
use Generators\ConfigGenerator;
use Helpers\InputHelper;
use Holders\ResultData;

/**
 * Description of CreateRoom
 */
//class CreateRoom extends BaseRace {

class CreateTutorialRoom  {

    public function Process(): ResultData {

        $userID = $_SESSION[Sessions::UserID];
        $userHandler = new UserHandler($userID);
        $userInfo = $userHandler->GetInfo();
        if ($userInfo->race !== RaceValue::NotInRace) {
            throw new RaceException(RaceException::UserInRace);
        }

        $lobby = RaceValue::LobbyCoinB;
        $version = InputHelper::post('version');
        $qualifyingHandler = new QualifyingHandler();
        $qualifyingHandler->CheckLobbyID($lobby);
        $userBagHandler = new UserBagHandler($userID);

        if ($qualifyingHandler->GetSeasonRemaintime($lobby) <= 0) {
            throw new RaceException(RaceException::NoSeasonData);
        }

        $useTicketId = RaceUtility::GetTicketID($lobby);
        $ticketCost = RaceUtility::GetTicketCost($lobby);
        if (($useTicketId !== RaceValue::NoTicketID) && ($userBagHandler->GetItemAmount($useTicketId) < $ticketCost)) {
            throw new RaceException(RaceException::UserTicketNotEnough);
        }
        
        if (RaceUtility::CheckPlayerID($lobby, $userInfo->player) == false)
        {
            throw new RaceException(RaceException::UsePlayerError);
        }

        $raceRoomID = RaceValue::NotInRoom;
        $accessor = new PDOAccessor(EnvVar::DBMain);

        $scendID = ConfigGenerator::Instance()->TutorialSceneID;

        $accessor->Transaction(function () use ($accessor, $userID, $lobby, $version, &$raceRoomID, $scendID) {
            $userInfo = $accessor->FromTable('Users')->WhereEqual('UserID', $userID)->ForUpdate()->Fetch();

            if ($userInfo->Room != RaceValue::NotInRoom) {

                //fix 4016
                $raceroomHandler = new RaceRoomsHandler();
                $roomInfo = $raceroomHandler->GetRoomInfo($userInfo->Room);
                if ($roomInfo->Status !== RaceValue::RoomClose) {
                    throw new RaceException(RaceException::UserInMatch);
                }
            }
            //todo
            $lowbound = 0;
            $upbound = 0;
            //
            $raceroomHandler = new RaceRoomsHandler();

            $raceRoom = $raceroomHandler->GetIdleRoom($lobby, $version, $lowbound, $upbound);
            $raceroomHandler->JoinRoom($userID, $raceRoom);
            $raceRoomID = $raceRoom->RaceRoomID;

            $accessor->ClearCondition();
            $accessor->FromTable('Users')->WhereEqual('UserID', $userID)->Modify([
                'Lobby' => $lobby,
                'Room' => $raceRoom->RaceRoomID,
                'Scene' => $scendID,
                'UpdateTime' => $GLOBALS[Globals::TIME_BEGIN]
            ]);
        });

        UserPool::Instance()->Delete($userID);
        
        $result = new ResultData(ErrorCode::Success);
        $result->raceRoomID = $raceRoomID;
        return $result;
    }

}