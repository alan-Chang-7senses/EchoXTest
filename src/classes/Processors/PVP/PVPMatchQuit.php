<?php

namespace Processors\PVP;

use Consts\EnvVar;
use Consts\Globals;
use Consts\Sessions;
use Consts\ErrorCode;
use Holders\ResultData;
use Games\Pools\UserPool;
use Accessors\PDOAccessor;
use Processors\Races\BaseRace;
use Games\PVP\RaceRoomsHandler;
use Games\Exceptions\RaceException;
use Exception;
class PVPMatchQuit extends BaseRace
{

    protected bool|null $mustInRace = false;
    public function Process(): ResultData
    {
        $accessor = new PDOAccessor(EnvVar::DBMain);
        $userID = $_SESSION[Sessions::UserID];
        try {
            $accessor->Transaction(function () use ($accessor, $userID) {
                $userInfo = $accessor->FromTable('Users')->WhereEqual('UserID', $userID)->ForUpdate()->Fetch();
                if ($userInfo->Room == 0) {
                    throw new RaceException(RaceException::UserNotInMatch);
                }
                $raceroomHandler = new RaceRoomsHandler();
                $raceroomHandler->LeaveSeat($userID, $userInfo->Room);

                $accessor->ClearCondition();
                $accessor->FromTable('Users')->WhereEqual('UserID', $userID)->Modify([
                    'Lobby' => 0,
                    'Room' => 0,
                    'UpdateTime' => $GLOBALS[Globals::TIME_BEGIN]
                ]);
            });
        }
        catch (exception $ex) {
            throw new RaceException($ex->getCode());
        }
        UserPool::Instance()->Delete($userID);
        $result = new ResultData(ErrorCode::Success);
        return $result;
    }
}