<?php

namespace Games\Accessors;

use Consts\Globals;

class PVEAccessor extends BaseAccessor
{
    public function rowsInfoByID(int $id) : mixed
    {
        return $this->StaticAccessor()
        ->FromTableJoinUsing('PVELevel','PVELevelAI','INNER','LevelID')
        ->WhereEqual('LevelID',$id)
        ->FetchAll();
    }
    public function rowsInfoByChapterID(int $id) : mixed
    {
        $rows = $this->StaticAccessor()
        ->FromTableJoinUsing('PVEChapter','PVELevel','INNER','ChapterID')
        ->WhereEqual('ChapterID',$id)
        ->FetchAll();
        return $rows;
    }

    public function AddClearInfo(int $userID, int $levelID, int $medalAmount) : bool
    {
        return $this->MainAccessor()
                ->FromTable('UserPVELevel')
                ->Add([
                        'UserID' => $userID,
                        'LevelID' => $levelID,
                        'MedalAmount' => $medalAmount,
                        'Time' => $GLOBALS[Globals::TIME_BEGIN],
                     ],true);
    }
}