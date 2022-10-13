<?php

namespace Games\Accessors;

use Consts\ErrorCode;
use Consts\Globals;
use Consts\Sessions;
use Games\Consts\PVEValue;
use PDOException;

class PVEAccessor extends BaseAccessor
{

    public function rowInfoByLevelID(int $id) : mixed
    {
        return $this->StaticAccessor()
        ->FromTableJoinUsing('PVELevel','PVEChapter','INNER','ChapterID')
        ->WhereEqual('LevelID',$id)
        ->Fetch();
    }
    public function rowsInfoByChapterID(int $id) : mixed
    {
        $rows = $this->StaticAccessor()
        ->FromTableJoinUsing('PVEChapter','PVELevel','INNER','ChapterID')
        ->WhereEqual('ChapterID',$id)
        ->FetchAll();
        return $rows;
    }

    public function rowPVERaceRoomInfoByRoomID(int $roomID) : mixed
    {
        return $this->MainAccessor()
        ->FromTable('PVERooms')
        ->WhereEqual('PVERoomID',$roomID)
        ->Fetch();
    }



    public function AddLevelInfo($bind) : bool
    {
        $bind['UpdateTime'] = $GLOBALS[Globals::TIME_BEGIN];
        return $this->MainAccessor()
                ->FromTable('UserPVELevel')
                ->Add($bind,true);
    }

    public function rowCurrentProcessingLevelByUserID(int $userID)
    {
        return $this->MainAccessor()
        ->FromTable('UserPVELevel')
        ->WhereEqual('UserID',$userID)
        ->WhereEqual('Status',PVEValue::LevelStatusProcessing)
        ->Fetch();
    }

    public function rowsChapterRewardRecordByUserID(int $userID,int $chapterID) : mixed
    {
        return $this->MainAccessor()
                    ->FromTable('UserPVEChapterReward')
                    ->WhereEqual('UserID', $userID)
                    ->WhereEqual('ChapterID',$chapterID)
                    ->FetchAll();
    }

    /**增加章節獎勵領賞紀錄。已領過獎將回傳false */
    public function AddChapterRewardInfo(int $chapterID, int $chapterRewardID) : bool
    {
        $userID = $_SESSION[Sessions::UserID];
        try
        {
            $this->MainAccessor()
                 ->FromTable('UserPVEChapterReward')
                 ->Add(
                    [
                        'UserID' => $userID,
                        'ChapterID' => $chapterID,
                        'ChapterRewardID' => $chapterRewardID,   
                    ]);
        }
        catch(PDOException $e)
        {
            if($e->errorInfo[0] == ErrorCode::PDODuplicate)
            return false;
        }
        return true;
    }

    public function AddPVEClearedLog(int $playerID, int $levelID, mixed $items,int $ranking, int $sync, float $startTime, int $clearCount = 1)
    {
        $bind = 
        [
            'UserID' => $_SESSION[Sessions::UserID],
            'PlayerID' => $playerID,
            'LevelID' => $levelID,
            'Items' => json_encode($items),
            'SyncRate' => $sync,
            'Ranking' => $ranking,
            'StartTime' => (int)$startTime,
            'ClearCount' => $clearCount,
            'FinishTime' => $GLOBALS[Globals::TIME_BEGIN],
        ];
        $this->LogAccessor()->FromTable('PVECleared')->Add($bind);
    }


    // public function AddPVERoom(int $levelID,array $seats) : bool|int
    // {
    //     $bind = 
    //     [
    //         'CreateTime' => $GLOBALS[Globals::TIME_BEGIN],
    //         'Status' => PVEValue::RoomStatusProcessing,
    //         'levelID' => $levelID,
    //         'RaceRoomSeats' => json_encode($seats),
    //     ];
    //     $this->MainAccessor()->FromTable('PVERooms')->Add($bind);
    //     return (int)$this->MainAccessor()->FromTable('PVERooms')->LastInsertID();
    // }
    
    // public function UpdatePVERoom(int $pveRoomID,array $bind)
    // {
    //     $this->MainAccessor()->FromTable('PVERooms')
    //                         ->WhereEqual('PVERoomID',$pveRoomID)
    //                         ->Modify($bind);
    // }
}