<?php

namespace Processors\PVE;

use Consts\ErrorCode;
use Consts\Globals;
use Consts\Sessions;
use Games\Accessors\RaceRoomsAccessor;
use Games\Accessors\UserAccessor;
use Games\Consts\PVEValue;
use Games\Consts\RaceValue;
use Games\Exceptions\PVEException;
use Games\Pools\UserPool;
use Games\PVE\PVELevelHandler;
use Games\PVE\UserPVEHandler;
use Games\Users\UserHandler;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\BaseProcessor;

class PVEStart extends BaseProcessor
{
    public function Process(): ResultData
    {
        //未解鎖、體力不足。不予開始PVE
        $levelID = InputHelper::post('levelID');
        $version = InputHelper::post('version');
        //玩家正在PVE、PVP。不予開始PVE
        $userID = $_SESSION[Sessions::UserID];
        $userPVEHandler = new UserPVEHandler($userID);
        $userPVEInfo = $userPVEHandler->GetInfo();
        if($userPVEInfo->currentProcessingLevel !== null)
        throw new PVEException(PVEException::UserInPVE);

        $userHandler = new UserHandler($userID);
        // $userInfo = $userHandler->GetInfo();
        
        // $pveAccessor = new PVEAccessor();
        $pveHandler = new PVELevelHandler($levelID);
        $pveLevelInfo = $pveHandler->GetInfo();
        $seats = [];
        $seats[] = $userID;
        $seats = array_merge($seats,array_keys((array)$pveLevelInfo->aiInfo));
        $raceRoomAccessor = new RaceRoomsAccessor();
        $roomInfo = $raceRoomAccessor->AddNewRoom(RaceValue::LobbyPVE,$version,0,0);

        $userAccessor = new UserAccessor();
        $userAccessor->ModifyUserValuesByID($userID,
        [
            'Lobby' => RaceValue::LobbyPVE,
            'Room' => $roomInfo->RaceRoomID,
            'Scene' => $pveLevelInfo->sceneID,
            'UpdateTime' => $GLOBALS[Globals::TIME_BEGIN],
        ]);
        $raceRoomAccessor->Update($roomInfo->RaceRoomID,
        [
            'RaceRoomSeats' => json_encode($seats),
        ]);
        UserPool::Instance()->Delete($userID);
        $userPVEHandler->SaveLevel(['levelID' => $levelID, 'status' => PVEValue::LevelStatusProcessing]);

        $result = new ResultData(ErrorCode::Success);
        //還需要傳詳細資料：如AI DNA等
        return $result;
    }
}