<?php

namespace Games\Store;

use Consts\Globals;
use DateTime;
use Games\Consts\StoreValue;
use Games\Store\Holders\StoreRefreshTimeHolder;
use Generators\ConfigGenerator;

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

    private static function CheckTime(int $updateTime, string $checkTime): StoreRefreshTimeHolder {
        $checkTimeValue = (new DateTime($checkTime))->format('U');
        $nowtime = (int) $GLOBALS[Globals::TIME_BEGIN];
        $result = new StoreRefreshTimeHolder();
        if ($updateTime < $checkTimeValue) {
            //now day
            if ($nowtime > $checkTimeValue) {
                $result->needRefresh = true;
                $result->remainSeconds = $checkTimeValue + 86400 - $nowtime;
            } else {
                $result->needRefresh = false;
                $result->remainSeconds = $checkTimeValue - $nowtime;
            }
        } else {
            //next day
            $result->needRefresh = false;
            $result->remainSeconds = $checkTimeValue + 86400 - $nowtime;
        }
        return $result;
    }

    public static function CheckAutoRefreshTime(int $updateTime): StoreRefreshTimeHolder {
        //商品更新,回傳剩餘時間
        return self::CheckTime($updateTime, ConfigGenerator::Instance()->StoreAutoRefreshTime);
    }

    public static function CheckResetTime(int $updateTime): StoreRefreshTimeHolder {
        //每日重置刷新按鈕的時間,回傳剩餘時間
        return self::CheckTime($updateTime, ConfigGenerator::Instance()->StoreRefreshResetTime);
    }

}
