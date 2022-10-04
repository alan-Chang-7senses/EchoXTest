<?php

namespace Games\PVE;

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
}