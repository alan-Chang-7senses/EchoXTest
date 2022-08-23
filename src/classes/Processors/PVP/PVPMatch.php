<?php

namespace Processors\PVP;

use Consts\EnvVar;
use Consts\Globals;
use Consts\Sessions;
use Consts\ErrorCode;
use Holders\ResultData;
use Helpers\InputHelper;
use Games\Pools\UserPool;
use Accessors\PDOAccessor;
use Games\Races\RaceUtility;
use Processors\Races\BaseRace;
use Games\PVP\RaceRoomsHandler;
use Games\Users\UserBagHandler;
use Generators\ConfigGenerator;
use Games\PVP\QualifyingHandler;
use Games\Exceptions\RaceException;

class PVPMatch extends BaseRace
{

    protected bool|null $mustInRace = false;
    public function Process(): ResultData
    {
        $userID = $_SESSION[Sessions::UserID];
        $lobby = InputHelper::post('lobby');


        $qualifyingHandler = new QualifyingHandler();
        $qualifyingHandler->CheckLobbyID($lobby);
        $userBagHandler = new UserBagHandler($userID);

        if ($qualifyingHandler->GetSeasonRemaintime() <= 0) {
            throw new RaceException(RaceException::NoSeasonData);
        }

        $useTicketId = RaceUtility::GetTicketID($lobby);
        if ($userBagHandler->GetItemAmount($useTicketId) <= 0) {
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