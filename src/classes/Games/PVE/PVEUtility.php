<?php

namespace Games\PVE;

use Consts\Sessions;
use Games\Pools\ItemInfoPool;
use Games\Users\RewardHandler;
use stdClass;

class PVEUtility
{    
    public static function GetItemInfo(int $itemID,?int $holdAmount = null) : stdClass
    {
        $rt = new stdClass();
        $itemInfo = ItemInfoPool::Instance()->{ $itemID};
        $rt->itemID = $itemInfo->ItemID;
        $rt->itemName = $itemInfo->ItemName;
        $rt->description = $itemInfo->Description;
        $rt->itemType = $itemInfo->ItemType;
        $rt->useType = $itemInfo->UseType;
        $rt->stackLimit = $itemInfo->StackLimit;
        $rt->icon = $itemInfo->Icon;
        $rt->source = $itemInfo->Source;
        if($holdAmount !== null) $rt->holdAmount = $holdAmount;
        // $rt->requiredAmount = $requiredAmount;
        return $rt;
    }

    public static function GetItemsInfoByRewardHandler(RewardHandler $rewardHandler) : array
    {
        $rt = [];
        $rewardInfo = $rewardHandler->GetInfo();
        foreach($rewardInfo->Contents as $content)
        {
            $rt[] = self::GetItemInfo($content->ItemID);
        }
        return $rt;
    }

    public static function GetItemInfos(array $itemIDs) : array
    {
        $rt = [];
        foreach($itemIDs as $itemID)
        {
            $rt[] = self::GetItemInfo($itemID);
        }
        return $rt;
    }

    public static function GetUserProcessingLevelID() : int|null
    {
        $userPVEInfo = (new UserPVEHandler($_SESSION[Sessions::UserID]))->GetInfo();
        return $userPVEInfo->currentProcessingLevel;
    }

    public static function HandleRewardReturnValue(array $rewards) : array
    {
        $temp = [];
        foreach($rewards as $reward)
        {
            $temp[$reward->ItemID] = isset($temp[$reward->ItemID]) ? 
                                    $temp[$reward->ItemID] + $reward->Amount :
                                    $reward->Amount;
        }
        $rt = [];
        foreach($temp as $itemID => $amount)
        {
            $rt[] = 
            [
                'itemID' => $itemID,
                'icon' => ItemInfoPool::Instance()->{$itemID}->Icon,
                'amount' => $amount,
            ];
        }
        return $rt;
    }
        //獲取獎勵
    public static function GetLevelReward(int $levelID) : array
    {
        $userID = $_SESSION[Sessions::UserID];
        $userPVEHandler = new UserPVEHandler($userID);
        $levelInfo = (new PVELevelHandler($levelID))->GetInfo();
        $susRewardHandler = new RewardHandler($levelInfo->sustainRewardID);
        $susReward = array_values($susRewardHandler->GetItems()); 

        //已通關過。
        if($userPVEHandler->HasClearedLevel($levelInfo->chapterID,$levelID))
        {
            return $susReward;
        }
        $firstRewardHandler = new RewardHandler($levelInfo->firstRewardID);
        $firstReward = array_values($firstRewardHandler->GetItems());
        return array_merge($firstReward,$susReward);        
    }


}