<?php

namespace Games\Store;

use Consts\EnvVar;
use Consts\Globals;
use Consts\Predefined;
use DateTime;
use Games\Consts\ItemValue;
use Games\Consts\StoreValue;
use Games\Store\Holders\StoreInfosHolder;
use Games\Store\Holders\StoreRefreshTimeHolder;
use Generators\ConfigGenerator;
use stdClass;

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
            $seconds = $checkTimeValue - $updateTime;
            $result->needRefresh = ($seconds > 86400);
        } else {
            $result->needRefresh = false;
        }

        if ($nowtime > $checkTimeValue) {
            $result->remainSeconds = $checkTimeValue + 86400 - $nowtime;
        } else {
            $result->remainSeconds = $checkTimeValue - $nowtime;
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

    public static function GetCurrencyItemID(int $currency): int {
        return match ($currency) {
            StoreValue::CurrencyMars => ItemValue::CurrencyCoin,
            StoreValue::CurrencyDiamond => ItemValue::CurrencyDiamond,
            StoreValue::CurrencyPT => ItemValue::CurrencyPetaToken,
            StoreValue::CurrencyMarsTicket => ConfigGenerator::Instance()->PvP_B_TicketId_1,
            StoreValue::CurrencyPtTicket => ConfigGenerator::Instance()->PvP_B_TicketId_2,
            StoreValue::CurrencyGroupTicket => ConfigGenerator::Instance()->PvP_B_TicketId_3,
        };
    }

    public static function GetStoreInfosHolder(stdClass|null $row): StoreInfosHolder {
        $holder = new StoreInfosHolder ();

        if (empty($row)) {
            $holder->storeInfoID = StoreValue::NoStoreInfoID; //沒有資料
            $holder->userID = 0;
            $holder->storeID = 0;
            $holder->fixTradIDs = "";
            $holder->randomTradIDs = "";
            $holder->refreshRemainAmounts = 0;
            $holder->createTime = 0;
            $holder->updateTime = 0;
        } else {
            $holder->storeInfoID = $row->StoreInfoID;
            $holder->userID = $row->UserID;
            $holder->storeID = $row->StoreID;
            $holder->fixTradIDs = $row->FixTradIDs;
            $holder->randomTradIDs = $row->RandomTradIDs;
            $holder->refreshRemainAmounts = $row->RefreshRemainAmounts;
            $holder->createTime = $row->CreateTime;
            $holder->updateTime = $row->UpdateTime;
        }
        return $holder;
    }

    public static function GetCallbackkey($device): string {

        if (getenv(EnvVar::SysEnv) == Predefined::SysLocal) {
            return match ($device) {
                StoreValue::iOS => getenv(EnvVar::CallBackKeyAndroidTest),
            };
        } else {
            return match ($device) {
                0 => getenv(EnvVar::CallBackKeyAndroidProduct),
            };
        }
    }

    public static function GetMd5Sign($callbackkey) {
        $params = $_POST;
        unset($params['sign']);
        ksort($params);
        $signKey = '';
        foreach ($params as $key => $val) {
            $signKey .= $key . '=' . $val . '&';
        }
        $signKey .= $callbackkey;
        return md5($signKey);
    }

}
