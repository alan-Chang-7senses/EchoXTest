<?php

namespace Processors\PVP;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\Globals;
use Consts\Sessions;
use Games\Exceptions\RaceException;
use Games\PVP\QualifyingHandler;
use Games\PVP\RaceRoomsHandler;
use Helpers\InputHelper;
use Holders\ResultData;

/**
 * Description of JoinRoom 
 */
class JoinRoom {

    protected bool|null $mustInRace = false;

    public function Process(): ResultData {

        $userID = $_SESSION[Sessions::UserID];
        $raceRoomID = InputHelper::post('raceRoomID');
        $qualifyingHandler = new QualifyingHandler();
        $accessor = new PDOAccessor(EnvVar::DBMain);
        $accessor->Transaction(function () use ($accessor, $qualifyingHandler, $userID, &$raceRoomID) {

            $userInfo = $accessor->FromTable('Users')->WhereEqual('UserID', $userID)->ForUpdate()->Fetch();
            if ($userInfo->Room != 0) {
                throw new RaceException(RaceException::UserInMatch);
            }

            $raceroomHandler = new RaceRoomsHandler();
            $raceRoom = $raceroomHandler->GetRoomInfo($raceRoomID);
            if ($raceRoom === false) {
                throw new RaceException(RaceException::UserMatchError);
            }

            if (in_array($raceRoom->Lobby, QualifyingHandler::MatchLobbies)) {
                throw new RaceException(RaceException::UserMatchError);
            }

            $raceroomHandler->JoinRoom($userID, $raceRoom);
            $accessor->ClearCondition();
            $accessor->FromTable('Users')->WhereEqual('UserID', $userID)->Modify([
                'Lobby' => $raceRoom->Lobby,
                'Room' => $raceRoom->RaceRoomID,
                'Scene' => $qualifyingHandler->GetSceneID($raceRoom->Lobby),
                'UpdateTime' => $GLOBALS[Globals::TIME_BEGIN]
            ]);
        });

        $result = new ResultData(ErrorCode::Success);
        return $result;
    }

}
