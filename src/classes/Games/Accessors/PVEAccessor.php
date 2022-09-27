<?php

namespace Games\Accessors;

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
}