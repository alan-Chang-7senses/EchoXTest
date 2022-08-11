<?php
namespace Games\Users;

use stdClass;
use Games\Pools\ItemInfoPool;

class ItemUtility
{
    public static function GetClientSimpleInfo(int $itemID, int $amount) : stdClass{

        $itemInfo = ItemInfoPool::Instance()->{ $itemID};
        $result = new stdClass();
        $result->itemID = $itemID;
        $result->amount = $amount;
        $result->icon = $itemInfo->Icon;
        return $result;
    }
}