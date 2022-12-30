<?php

namespace Games\Users;

use stdClass;
use Games\Pools\ItemInfoPool;

class ItemUtility {

    public static function GetClientSimpleInfo(int $itemID, int $amount): stdClass {

        $itemInfo = ItemInfoPool::Instance()->{ $itemID};
        $result = new stdClass();
        $result->itemID = $itemID;
        $result->amount = $amount;
        $result->icon = $itemInfo->Icon;
        return $result;
    }

    //轉為背包使用的物品格式
    public static function GetBagItem(int $itemID, int $amount): stdClass {
        $result = new stdClass();
        $result->ItemID = $itemID;
        $result->Amount = $amount;
        return $result;
    }

    /**
     * 累加物品
     * @param array $items <p>被累加陣列</p>
     * @param stdClass|array $addItems <p>新加入物品資訊,字首大寫,需含ItemID、Amount</p>
     */
    public static function AccumulateItems(array &$items, stdClass|array $addItems) {

        if (is_array($addItems)) {
            foreach ($addItems as $addItem) {
                self::AccumulateItem($items, $addItem);
            }
        } else {
            self::AccumulateItem($items, $addItems);
        }
    }

    private static function AccumulateItem(array &$items, stdClass $addItem) {
        if (isset($items[$addItem->ItemID])) {
            $items[$addItem->ItemID]->Amount += $addItem->Amount;
        } else {
            $items[$addItem->ItemID] = clone $addItem;
        }
    }

}
