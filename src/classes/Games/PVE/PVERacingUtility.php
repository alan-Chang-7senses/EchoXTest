<?php

namespace Games\PVE;

use Consts\Sessions;
use Games\Players\PlayerHandler;
use Games\Players\PlayerUtility;
use Games\Users\RewardHandler;

class PVERacingUtility
{
        
    //獲取獎勵
    public static function GetLevelReward(int $levelID) : array
    {
        $userID = $_SESSION[Sessions::UserID];
        $userPVEHandler = new UserPVEHandler($userID);
        $levelInfo = (new PVELevelHandler($levelID))->GetInfo();
        $susRewardHandler = new RewardHandler($levelInfo->sustainRewardID);
        $susReward = array_values($susRewardHandler->GetItems()); 

        //已通關過。
        if($userPVEHandler->HasClearedLevel($levelInfo->chapterID,$levelID))
        {
            return $susReward;
        }
        $firstRewardHandler = new RewardHandler($levelInfo->firstRewardID);
        $firstReward = array_values($firstRewardHandler->GetItems());
        return array_merge($firstReward,$susReward);        
    }
    
    public static function GetBotInfo(int $playerID) : array
    {
        $playerHandler = new PlayerHandler($playerID);
        $playerInfo = $playerHandler->GetInfo();
        $player = clone $playerInfo;
        PlayerUtility::PartcodeAllDNA($player->dna);
        $info = 
        [
            'id' => $player->id,
            'name' => $player->name,
            'dna' => $player->dna,
        ];
        
        return $info;    
    }

    


    // private static function ReleasePVERaceRoom(UserHandler $userHandler)
    // {
    //     $userInfo = $userHandler->GetInfo();
    //     //清房間。
    //     $pveAccessor = new PVEAccessor();
    //     $pveAccessor->UpdatePVERoom($userInfo->room,['Status' => PVEValue::RoomStatusFinish]);
    // }

    // private static function ReleasePVERace(RaceHandler $raceHandler)
    // {
    //     (new UserAccessor())->ModifyUserValuesByID($_SESSION[Sessions::UserID],
    //     [
    //         'Lobby' => RaceValue::NotInLobby,
    //         'Room' => RaceValue::NotInRoom,
    //         'Race' => RaceValue::NotInRace,
    //     ]);
    //     UserPool::Instance()->Delete($_SESSION[Sessions::UserID]);
    //     $raceHandler->SaveData(['status' => RaceValue::StatusFinish,'finishTime' => $GLOBALS[Globals::TIME_BEGIN]]);
    //     $raceInfo = $raceHandler->GetInfo();
    //     foreach($raceInfo->racePlayers as $racePlayerID)
    //     {
    //         $racePlayerHandler = new RacePlayerHandler($racePlayerID);
    //         $racePlayerHandler->SaveData(['status' => RaceValue::StatusFinish,'finishTime' => $GLOBALS[Globals::TIME_BEGIN]]);
    //     } 
    // }
}