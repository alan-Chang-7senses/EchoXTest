<?php

namespace Games\PVE;

use Games\Accessors\PVEAccessor;
use Games\Consts\PVEValue;
use Games\Pools\UserPVEPool;
use Games\PVE\Holders\UserPVEInfoHolder;
use Games\Users\RewardHandler;
use Processors\EliteTest\FastestList;
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
        if($info->clearLevelInfo instanceof stdClass)
        {
            $infoTemp = [];
            foreach($info->clearLevelInfo as $chapterID => $chapterInfo)
            {
                foreach($chapterInfo as $levelID => $medalAmount)
                {
                    $infoTemp[$chapterID][$levelID] = $medalAmount;
                }
            }
            $info->clearLevelInfo = $infoTemp;
        }
    }


    /**
     * @return int 目前解鎖的所有章節
     * @return bool 若尚未進行過PVE。回傳false
     */
    public function GetChapterProcess() : array | bool
    {
       if(empty($this->GetInfo()->clearLevelInfo))return false; 
       $chapterIDs = array_keys($this->GetInfo()->clearLevelInfo);
    //    sort($chapterIDs);
       return $chapterIDs;
    }

    public function HasClearedLevel(int $chapterID,int $levelID) : bool
    {
        $info = $this->GetInfo();
        if(isset($info->clearLevelInfo[$chapterID][$levelID]))
        {
            $medalAmount = $info->clearLevelInfo[$chapterID][$levelID];
            return $medalAmount > 0;
        }
        return false;
    }

    public function GetLevelMedalAmount(int $levelID) : int
    {
        $chapterID = (new PVELevelHandler($levelID))->GetInfo()->chapterID;
        if(!$this->HasClearedLevel($chapterID,$levelID))return 0;
        $info = $this->GetInfo();
        return $info->clearLevelInfo[$chapterID][$levelID];
    }
}