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
use Games\PVP\RaceRoomsHandler;
use Holders\ResultData;
use Processors\Races\BaseRace;

class PVPMatchQuit extends BaseRace {

    protected bool|null $mustInRace = false;

    public function Process(): ResultData {
        $accessor = new PDOAccessor(EnvVar::DBMain);
        $userID = $_SESSION[Sessions::UserID];
        $accessor->Transaction(function () use ($accessor, $userID) {
            $userInfo = $accessor->FromTable('Users')->WhereEqual('UserID', $userID)->ForUpdate()->Fetch();
            if ($userInfo->Room == RaceValue::NotInRoom) {
                throw new RaceException(RaceException::UserNotInMatch);
            }
            $raceroomHandler = new RaceRoomsHandler();
            $raceroomHandler->LeaveRoom($userID, $userInfo->Room);

            $accessor->ClearCondition();
            $accessor->FromTable('Users')->WhereEqual('UserID', $userID)->Modify([
                'Lobby' => RaceValue::LobbyNone,
                'Room' => RaceValue::NotInRoom,
                'UpdateTime' => $GLOBALS[Globals::TIME_BEGIN]
            ]);
        });

        UserPool::Instance()->Delete($userID);
        $result = new ResultData(ErrorCode::Success);
        return $result;
    }

}
