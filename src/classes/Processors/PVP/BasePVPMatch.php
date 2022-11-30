<?php

namespace Processors\PVP;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\Globals;
use Consts\Sessions;
use Games\Consts\RaceValue;
use Games\Exceptions\RaceException;
use Games\Pools\UserPool;
use Games\PVP\CompetitionsInfoHandler;
use Games\PVP\Holders\CompetitionsInfoHolder;
use Games\PVP\QualifyingHandler;
use Games\PVP\RaceRoomsHandler;
use Games\Races\RaceUtility;
use Games\Users\UserBagHandler;
use Games\Users\UserHandler;
use Helpers\InputHelper;
use Processors\BaseProcessor;
use stdClass;

abstract class BasePVPMatch extends BaseProcessor {

    protected CompetitionsInfoHolder|stdClass $competitionsInfo;

    protected function Matching(bool $isCreateRoom): int {

        $userID = $_SESSION[Sessions::UserID];
        $userHandler = new UserHandler($userID);
        $userInfo = $userHandler->GetInfo();
        if ($userInfo->race !== RaceValue::NotInRace) {
//            $raceHandler = new RaceHandler($userInfo->race);
//            $raceInfo = $raceHandler->GetInfo();
//
//            if ($GLOBALS[Globals::TIME_BEGIN] - $raceInfo->createTime > ConfigGenerator::Instance()->TimelimitRaceFinish) {
//
//                $accessor = new PDOAccessor(EnvVar::DBMain);
//
//                $raceUsers = $accessor->FromTable('Users')->WhereEqual('Race', $userInfo->race)->FetchAll();
//                if ($raceUsers !== false) {
//                    foreach ($raceUsers as $raceUser) {
//
//                        $accessor->ClearCondition()->FromTable('Users')->WhereEqual('UserID', $raceUser->UserID)->Modify([
//                            'Race' => RaceValue::NotInRace,
//                            'Lobby' => RaceValue::LobbyNone,
//                            'Room' => RaceValue::NotInRoom,
//                            'UpdateTime' => $GLOBALS[Globals::TIME_BEGIN]
//                        ]);
//                        UserPool::Instance()->Delete($raceUser->UserID);
//                    }
//                }
//
//                $accessor->ClearCondition()->FromTable('Races')->WhereEqual('RaceID', $userInfo->race)->Modify([
//                    'Status' => RaceValue::StatusFinish,
//                    'UpdateTime' => $GLOBALS[Globals::TIME_BEGIN]
//                ]);
//                RacePool::Instance()->Delete($userInfo->race);
//            } else {
                throw new RaceException(RaceException::UserInRace);
//            }
        }

        $lobby = InputHelper::post('lobby');
        $version = InputHelper::post('version');
        $qualifyingHandler = new QualifyingHandler();
        $qualifyingHandler->CheckLobbyID($lobby);
        $userBagHandler = new UserBagHandler($userID);

        // 配合賽季結束時間 PvP_B_StopMatch
        if ($qualifyingHandler->GetSeasonRemaintime($lobby) <= 0) {
            throw new RaceException(RaceException::NoSeasonData);
        }

        $useTicketId = RaceUtility::GetTicketID($lobby);
        if (($useTicketId !== RaceValue::NoTicketID) && ($userBagHandler->GetItemAmount($useTicketId) <= 0)) {
            throw new RaceException(RaceException::UserTicketNotEnough);
        }

        if (RaceUtility::CheckPlayerID($lobby, $userInfo->player) == false) {
            throw new RaceException(RaceException::UsePlayerError);
        }

        $raceRoomID = RaceValue::NotInRoom;
        $accessor = new PDOAccessor(EnvVar::DBMain);

        $accessor->Transaction(function () use ($accessor, $qualifyingHandler, $userID, $lobby, $version, &$raceRoomID, $isCreateRoom) {
            $userInfo = $accessor->FromTable('Users')->WhereEqual('UserID', $userID)->ForUpdate()->Fetch();
            if ($userInfo->Room != RaceValue::NotInRoom) {

                //fix 4016
                $raceroomHandler = new RaceRoomsHandler();
                $roomInfo = $raceroomHandler->GetRoomInfo($userInfo->Room);
                if ($roomInfo->Status !== RaceValue::RoomClose) {
                    throw new RaceException(RaceException::UserInMatch);
                }
            }
            //Get bounds
            $seasonID = $qualifyingHandler->GetSeasonIDByLobby($lobby);
            $competitionsInfoHandler = new CompetitionsInfoHandler($lobby);
            $this->competitionsInfo = $competitionsInfoHandler->GetInfo();
            $accessor->ClearCondition();
            $row = $accessor->FromTable('LeaderboardRating')
                    ->WhereEqual('PlayerID', $userInfo->Player)
                    ->WhereEqual('SeasonID', $seasonID)
                    ->WhereEqual('Lobby', $lobby)
                    ->Fetch();

            if (empty($row)) {
                $rating = $this->competitionsInfo->baseRating;
            } else {
                $rating = $row->Rating;
            }

            $lowbound = $rating - $this->competitionsInfo->matchingRange;
            $upbound = $rating + $this->competitionsInfo->matchingRange;
            //
            $raceroomHandler = new RaceRoomsHandler();

            if ($isCreateRoom) {
                $raceRoom = $raceroomHandler->GetIdleRoom($lobby, $version, $lowbound, $upbound);
            } else {
                $raceRoom = $raceroomHandler->GetMatchRoom($lobby, $version, $lowbound, $upbound, $rating);
            }
            $raceroomHandler->JoinRoom($userID, $raceRoom);
            $raceRoomID = $raceRoom->RaceRoomID;

            $accessor->ClearCondition();
            $accessor->FromTable('Users')->WhereEqual('UserID', $userID)->Modify([
                'Lobby' => $lobby,
                'Room' => $raceRoom->RaceRoomID,
                'Scene' => $qualifyingHandler->GetSceneID($lobby, $userInfo->Scene),
                'UpdateTime' => $GLOBALS[Globals::TIME_BEGIN]
            ]);
        });

        UserPool::Instance()->Delete($userID);
        return $raceRoomID;
    }

}
