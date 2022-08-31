<?php

namespace Processors\PVP;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\Globals;
use Consts\Sessions;
use Games\Consts\RaceValue;
use Games\Exceptions\RaceException;
use Games\Pools\RacePool;
use Games\Pools\UserPool;
use Games\PVP\QualifyingHandler;
use Games\PVP\RaceRoomsHandler;
use Games\Races\RaceHandler;
use Games\Races\RaceUtility;
use Games\Users\UserBagHandler;
use Games\Users\UserHandler;
use Generators\ConfigGenerator;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\BaseProcessor;

class PVPMatch extends BaseProcessor {

    public function Process(): ResultData {

        $userID = $_SESSION[Sessions::UserID];
        $userHandler = new UserHandler($userID);
        $userInfo = $userHandler->GetInfo();
        if ($userInfo->race !== RaceValue::NotInRace) {
            $raceHandler = new RaceHandler($userInfo->race);
            $raceInfo = $raceHandler->GetInfo();           

            if ($GLOBALS[Globals::TIME_BEGIN] - $raceInfo->createTime > ConfigGenerator::Instance()->TimelimitElitetestRace) {

                $accessor = new PDOAccessor(EnvVar::DBMain);

                $raceUsers = $accessor->FromTable('Users')->WhereEqual('Race', $userInfo->race)->FetchAll();
                if ($raceUsers !== false) {
                    foreach ($raceUsers as $raceUser) {

                        $accessor->ClearCondition()->FromTable('Users')->WhereEqual('UserID', $raceUser->UserID)->Modify([
                            'Race' => RaceValue::NotInRace,
                            'Lobby' => RaceValue::LobbyNone,
                            'Room' => RaceValue::NotInRoom,
                            'UpdateTime' => $GLOBALS[Globals::TIME_BEGIN]
                        ]);
                        UserPool::Instance()->Delete($raceUser->UserID);
                    }
                }

                $accessor->ClearCondition()->FromTable('Races')->WhereEqual('RaceID', $userInfo->race)->Modify([
                    'Status' => RaceValue::StatusFinish,
                    'UpdateTime' => $GLOBALS[Globals::TIME_BEGIN]
                ]);
                RacePool::Instance()->Delete($userInfo->race);
            } else {
                throw new RaceException(RaceException::UserInRace);
            }
        }

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
            $raceRoom = $raceroomHandler->GetMatchRoom($lobby, $lowbound, $upbound);
            $raceroomHandler->JoinRoom($userID, $raceRoom);
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
