<?php

namespace Games\PVE;

use Games\Consts\PVEValue;
use Games\Pools\UserPVEPool;
use Games\PVE\Holders\UserPVEInfoHolder;
use stdClass;

class UserPVEHandler
{
    private UserPVEPool $pool;
    private int|string $id;
    private UserPVEInfoHolder|stdClass $info;
    
    public function __construct(int|string $userID) 
    {
        $this->pool = UserPVEPool::Instance();
        $this->id = $userID;
        $info = $this->pool->$userID;
        $this->info = $info;
    }    

    public function GetInfo() : UserPVEInfoHolder|stdClass
    {
        self::LevelInfoToArray($this->info);
        return $this->info;
    }
    public function SaveLevel(array $bind) : UserPVEInfoHolder|stdClass
    {
        $bind['userID'] = $this->id;
        $this->pool->Save($this->id, 'Level', $bind);
        return $this->ResetInfo();
    }
    private function ResetInfo() : UserPVEInfoHolder|stdClass
    {
        $this->info = $this->pool->{$this->id};
        self::LevelInfoToArray($this->info);
        return $this->info;
    }

    public static function LevelInfoToArray(stdClass $info) : void
    {        
        if(!empty($info->levelProcess) && $info->levelProcess instanceof stdClass)
        {
            $infoTemp = [];
            foreach($info->levelProcess as $chapterID => $chapterInfo)
            {
                foreach($chapterInfo as $levelID => $medalAmount)
                {
                    $infoTemp[$chapterID][$levelID] = $medalAmount;
                }
            }
            $info->levelProcess = $infoTemp;
        }
    }

    public function HasClearedLevel(int $chapterID,int $levelID) : bool
    {
        $info = $this->GetInfo();
        if(isset($info->levelProcess[$chapterID][$levelID]))
        {
            $medalAmount = $info->levelProcess[$chapterID][$levelID];
            return $medalAmount > 0;
        }
        return false;
    }

    public function GetLevelMedalAmount(int $levelID) : int
    {
        $chapterID = (new PVELevelHandler($levelID))->GetInfo()->chapterID;
        if(!$this->HasClearedLevel($chapterID,$levelID))return 0;
        $info = $this->GetInfo();
        return $info->levelProcess[$chapterID][$levelID];
    }

    public function GetChapterMedalAmount(int $chapterID) : int
    {
        $info = $this->GetInfo();
        if(!isset($info->levelProcess[$chapterID]))return 0;
        return array_sum($info->levelProcess[$chapterID]);
    }

    public function IsLevelUnLock(stdClass $levelInfo) : bool
    {
        if(empty($levelInfo->preLevels))return true;
        foreach($levelInfo->preLevels as $preLevel)
        {
            $preLevelInfo = (new PVELevelHandler($preLevel))->GetInfo();
            if($this->GetLevelMedalAmount($preLevelInfo->levelID) < PVEValue::LevelUnlockMedalAmount)
            return false;
        }
        return true;
    }

    public function IsChapterUnlock(int $chapterID) : bool
    {
        $chapterInfo = PVEChapterData::GetChapterInfo($chapterID);
        if(!empty($chapterInfo->preChapters))
        {
            foreach($chapterInfo->preChapters as $preChapter)
            {
                $preChapterInfo = PVEChapterData::GetChapterInfo($preChapter);
                foreach($preChapterInfo->levels as $level)
                {
                    $levelInfo = (new PVELevelHandler($level))->GetInfo();
                    $isPerfectClear = $this->GetLevelMedalAmount($levelInfo->levelID) == PVEValue::LevelUnlockMedalAmount;
                    if(!$isPerfectClear)return false;
                }
            }
        }
        return true;
    }
}