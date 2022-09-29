<?php

namespace Games\PVE;

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
        $this->ClearLevelInfoToArray();
        return $this->info;
    }
    public function SaveClearLevel(int $levelID,int $medalAmount) : UserPVEInfoHolder|stdClass
    {
        $bind = ['userID' => $this->id, 'levelID' => $levelID, 'medalAmount' => $medalAmount];
        $this->pool->Save($this->id, 'ClearLevel', $bind);
        return $this->ResetInfo();
    }
    private function ResetInfo() : UserPVEInfoHolder|stdClass
    {
        $this->info = $this->pool->{$this->id};
        $this->ClearLevelInfoToArray();
        return $this->info;
    }

    private function ClearLevelInfoToArray()
    {        
        if($this->info->clearLevelInfo instanceof stdClass)
        {
            $infoTemp = [];
            foreach($this->info->clearLevelInfo as $chapterID => $chapterInfo)
            {
                foreach($chapterInfo as $levelID => $medal)
                {
                    $infoTemp[$chapterID][$levelID] = $medal;
                }
            }
            $this->info->clearLevelInfo = $infoTemp;
        }
    }

    //紀錄成績、獲取獎勵
    public function ClearLevelAndGetReward(int $levelID,int $medalAmount) : array
    {
        // 獎牌數量可以從Raceplayer去判斷。讓流程傳入。
        $levelInfo = (new PVELevelHandler($levelID))->GetInfo();
        $susRewardHandler = new RewardHandler($levelInfo->sustainRewardID);
        $susReward = $susRewardHandler->GetItems(); 
        $info = $this->GetInfo();
        //已通過關
        if(isset($info->clearLevelInfo[$levelInfo->chapterID])
            && isset($info->clearLevelInfo[$levelInfo->chapterID][$levelID]))
        {
            $preMedalAmount = $this->info->clearLevelInfo[$levelInfo->chapterID][$levelID];
            if($preMedalAmount < $medalAmount)$this->SaveClearLevel($levelID,$medalAmount);
            $rt = []; //避免獎勵重複鍵值
            $rt[] = $susReward;
            return $rt;            
        }
        $firstRewardHandler = new RewardHandler($levelInfo->firstRewardID);
        $this->SaveClearLevel($levelID,$medalAmount);
        $firstReward = $firstRewardHandler->GetItems();
        return array_merge($firstReward,$susReward);        
    }

    /**
     * @return int 目前進行到的章節ID
     * @return bool 若尚未進行過PVE。回傳false
     */
    public function GetChapterProcess() : int | bool
    {
       if(empty($this->GetInfo()->clearLevelInfo))return false; 
       $chapterIDs = array_keys($this->GetInfo()->clearLevelInfo);
       sort($chapterIDs);
       return $chapterIDs[count($chapterIDs) - 1];
    }
}