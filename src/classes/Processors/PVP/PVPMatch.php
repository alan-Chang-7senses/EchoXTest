<?php

namespace Processors\PVP;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\Globals;
use Consts\Sessions;
use Games\Exceptions\RaceException;
use Games\Pools\UserPool;
use Games\PVP\QualifyingHandler;
use Games\PVP\RaceRoomsHandler;
use Games\Races\RaceUtility;
use Games\Users\UserBagHandler;
use Generators\ConfigGenerator;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\Races\BaseRace;

class PVPMatch extends BaseRace {

    protected bool|null $mustInRace = false;

    public function Process(): ResultData {
        $userID = $_SESSION[Sessions::UserID];
        $lobby = InputHelper::post('lobby');

        $qualifyingHandler = new QualifyingHandler();
        $qualifyingHandler->CheckLobbyID($lobby);
        $userBagHandler = new UserBagHandler($userID);

        if ($qualifyingHandler->GetSeasonRemaintime() <= 0) {
            throw new RaceException(RaceException::NoSeasonData);
        }

        $useTicketId = RaceUtility::GetTicketID($lobby);
        if (($useTicketId !== 0) && ($userBagHandler->GetItemAmount($useTicketId) <= 0)) {
            throw new RaceException(RaceException::UserTicketNotEnough);
        }

        $raceRoomID = 0;
        $accessor = new PDOAccessor(EnvVar::DBMain);

        $accessor->Transaction(function () use ($accessor, $qualifyingHandler, $userID, $lobby, &$raceRoomID) {
            $userInfo = $accessor->FromTable('Users')->WhereEqual('UserID', $userID)->ForUpdate()->Fetch();

            if ($userInfo->Room != 0) {
                throw new RaceException(RaceException::UserInMatch);
            }
            //todo
            $lowbound = 0;
            $upbound = 0;
            //
            $raceroomHandler = new RaceRoomsHandler();
            $raceRoom = $raceroomHandler->GetMatchRoom($lobby, $lowbound, $upbound, $qualifyingHandler->NowSeasonID);
            $raceroomHandler->TakeSeat($userID, $raceRoom);
            $raceRoomID = $raceRoom->RaceRoomID;

            $accessor->ClearCondition();
            $accessor->FromTable('Users')->WhereEqual('UserID', $userID)->Modify([
                'Lobby' => $lobby,
                'Room' => $raceRoom->RaceRoomID,
                'Scene' => $qualifyingHandler->GetSceneID($lobby),
                'UpdateTime' => $GLOBALS[Globals::TIME_BEGIN]
            ]);
        });

        UserPool::Instance()->Delete($userID);

        $result = new ResultData(ErrorCode::Success);
        $result->raceRoomID = $raceRoomID;
        $result->extraMatchSeconds = ConfigGenerator::Instance()->PvP_ExtraMatchSeconds;
        $result->maxMatchSeconds = ConfigGenerator::Instance()->PvP_MaxMatchSeconds;

        return $result;
    }

}
