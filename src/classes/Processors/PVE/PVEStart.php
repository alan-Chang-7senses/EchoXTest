<?php

namespace Processors\PVE;

use Consts\ErrorCode;
use Consts\Globals;
use Consts\Sessions;
use Games\Accessors\RaceRoomsAccessor;
use Games\Accessors\UserAccessor;
use Games\Consts\ActionPointValue;
use Games\Consts\PVEValue;
use Games\Consts\RaceValue;
use Games\Exceptions\PVEException;
use Games\Exceptions\RaceException;
use Games\Exceptions\UserException;
use Games\Pools\UserPool;
use Games\PVE\PVELevelHandler;
use Games\PVE\PVERacingUtility;
use Games\PVE\UserPVEHandler;
use Games\Users\UserHandler;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\BaseProcessor;

class PVEStart extends BaseProcessor
{
    public function Process(): ResultData
    {
        $levelID = InputHelper::post('levelID');
        $version = InputHelper::post('version');
        $userID = $_SESSION[Sessions::UserID];
        $userPVEHandler = new UserPVEHandler($userID);
        $userHandler = new UserHandler($userID);
        $userPVEInfo = $userPVEHandler->GetInfo();

        if($userPVEInfo->currentProcessingLevel !== null || $userHandler->GetInfo()->race !== RaceValue::NotInRace)
        throw new RaceException(RaceException::UserInRace);

        $pveHandler = new PVELevelHandler($levelID);
        $pveLevelInfo = $pveHandler->GetInfo();
        
        
        
        
        $isUnlock = $userPVEHandler->IsChapterUnlock($pveLevelInfo->chapterID) && $userPVEHandler->IsLevelUnLock($pveLevelInfo);
        if(!$isUnlock) throw new PVEException(PVEException::LevelLock);
        
        if($userHandler->HandlePower(-$pveLevelInfo->power,ActionPointValue::CausePVENormal,$levelID) === false)
        throw new UserException(UserException::UserPowerNotEnough);

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
        $result->botInfos = [];
        foreach((array)$pveLevelInfo->aiInfo as $aiID => $trackNumber)
        {
            $temp = PVERacingUtility::GetBotInfo($aiID);
            $temp['TrackNumber'] = $trackNumber;
            $result->botInfos[] = $temp;
        }

        //還需要傳詳細資料：如AI DNA等
        return $result;
    }
}