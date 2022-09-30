<?php

namespace Processors\PVE;

use Consts\ErrorCode;
use Consts\Sessions;
use Games\PVE\PVEChapterData;
use Games\PVE\PVELevelHandler;
use Games\PVE\UserPVEHandler;
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
        $userPVEHandler = new UserPVEHandler($userID);
        $userPVEInfo = $userPVEHandler->GetInfo();
        $result = new ResultData(ErrorCode::Success);
        if(!isset($userPVEInfo->clearLevelInfo[$chapterID]))
        {
            //章節尚未解鎖，報錯。
        }
        $result->levels = [];
        //各等階獎牌數量。加上玩家目前此章節獎牌數量。
        foreach($chapterInfo->levels as $levelID)
        {
            $levelInfo = (new PVELevelHandler($levelID))->GetInfo();
            //是否有通關過。沒通關過不會有資料
            $medalAount = $userPVEHandler->HasClearedLevel($chapterID,$levelInfo->levelID) ?
                          $userPVEInfo->clearLevelInfo[$chapterID][$levelID] :
                          0;
            //是否已通過前置關卡                                
            $isUnlock = true;
            foreach($levelInfo->preLevels as $preLevel)
            if(!$userPVEHandler->HasClearedLevel($chapterID,$preLevel))$isUnlock = false;

            $level = 
            [
                'id' => $levelInfo->levelID,
                'name' => $levelInfo->levelName,
                'description' => $levelInfo->description,
                'recommendLevel' => $levelInfo->recommendLevel,
                'currentMedalAmount' => $medalAount,
                'isUnlock' => $isUnlock,
                'hasClear' => $medalAount > 0,
                'powerRequired' => $levelInfo->power,
            ];
            
        }                

        $userMedalAmount = array_sum($userPVEInfo->clearLevelInfo[$chapterID]);
        $result->name = $chapterInfo->name;
        $result->medalAmountFirst = $chapterInfo->medalAmountFirst;
        $result->rewardIDFirst = $chapterInfo->rewardIDFirst;
        $result->medalAmountSecond = $chapterInfo->medalAmountSecond;
        $result->rewardIDSecond = $chapterInfo->rewardIDSecond;
        $result->medalAmountThird = $chapterInfo->medalAmountThird;
        $result->rewardIDThrid = $chapterInfo->rewardIDThrid;

        
        
        return $result;
    }
}