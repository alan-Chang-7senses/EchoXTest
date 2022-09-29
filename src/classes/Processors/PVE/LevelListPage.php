<?php

namespace Processors\PVE;

use Consts\ErrorCode;
use Consts\Sessions;
use Games\PVE\PVEChapterData;
use Games\Users\UserHandler;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\BaseProcessor;

class LevelListPage extends BaseProcessor
{
    public function Process(): ResultData
    {
        $chapterID = InputHelper::post('chapterID');
        $userID = $_SESSION[Sessions::UserID];

        $chapterInfo = PVEChapterData::GetChapterInfo($chapterID);
        
        $result = new ResultData(ErrorCode::Success);
        return $result;
    }
}