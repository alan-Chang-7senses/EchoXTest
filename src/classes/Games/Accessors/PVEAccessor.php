<?php

namespace Games\Accessors;

use Consts\Globals;
use Games\Consts\PVEValue;

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