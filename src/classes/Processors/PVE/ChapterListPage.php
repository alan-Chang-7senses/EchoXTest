<?php

namespace Processors\PVE;

use Consts\ErrorCode;
use Consts\Sessions;
use Games\PVE\PVEChapterData;
use Games\PVE\UserPVEHandler;
use Games\Users\UserHandler;
use Holders\ResultData;
use Processors\BaseProcessor;

class ChapterListPage extends BaseProcessor
{
    public function Process(): ResultData
    {

        $userID = $_SESSION[Sessions::UserID];
        $userHandler = new UserHandler($userID);
        $userInfo = $userHandler->GetInfo();

        $userHandler->HandlePower(0);
        $chapterData = PVEChapterData::GetData();
        $result = new ResultData(ErrorCode::Success);
        $result->power = $userInfo->power;
        $result->chapters = [];
        $userPVEHandler = new UserPVEHandler($userID);
        $userPVEInfo = $userPVEHandler->GetInfo();
        $t = $userPVEHandler->GetChapterProcess();

        foreach($chapterData as $id => $chapter)
        {
            $temp = 
            [
                'id' => $id,
                'icon' => $chapter->icon,
                'name' => $chapter->name,
            ];
            $result->chapters[] = $temp;
        }        
        return $result;
    }
}