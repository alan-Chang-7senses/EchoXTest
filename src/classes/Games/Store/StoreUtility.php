<?php

namespace Games\Store;

use Games\Consts\StoreValue;

/*
 * Description of StoreUtility
 */

class StoreUtility {

    public static function GetMaxStoreAmounts(int $uitype): int {
        return match ($uitype) {
            StoreValue::UIType_12 => 12,
            StoreValue::UIType_08 => 8,
            StoreValue::UIType_04 => 4,
            StoreValue::UIType_00 => 0,
            default => StoreValue::UINoItems
        };
    }

}
