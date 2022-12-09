<?php

namespace Games\Store;

use Consts\EnvVar;
use Consts\Globals;
use DateTime;
use Games\Consts\ItemValue;
use Games\Consts\RaceValue;
use Games\Consts\StoreValue;
use Games\PVP\CompetitionsInfoHandler;
use Games\Store\Holders\StoreInfosHolder;
use Games\Store\Holders\StoreRefreshTimeHolder;
use Games\Users\UserBagHandler;
use Generators\ConfigGenerator;
use stdClass;

/*
 * Description of StoreUtility
 */

class StoreUtility {

    public static function GetMaxStoreAmounts(int $uitype): int {
        return match ($uitype) {//(固定件數)
            StoreValue::UIType_12 => 12,
            StoreValue::UIType_08 => 9,
            StoreValue::UIType_04 => 3,
            StoreValue::UIType_00 => 0,
            default => StoreValue::UIUnset
        };
    }

    private static function CheckTime(int $updateTime, string $checkTime): StoreRefreshTimeHolder {
        $checkTimeValue = (new DateTime($checkTime))->format('U');
        $nowtime = (int) $GLOBALS[Globals::TIME_BEGIN];
        $result = new StoreRefreshTimeHolder();
        $result->updateTime = $updateTime;

        if ($nowtime > $checkTimeValue) {
            $checkTimeValue = $checkTimeValue + 86400;
        }

        if ($updateTime < $checkTimeValue) {
            $seconds = $checkTimeValue - $updateTime;
            $result->needRefresh = ($seconds > 86400);
        } else {
            $result->needRefresh = false;
        }
        $result->remainSeconds = $checkTimeValue - $nowtime;

        return $result;
    }

    public static function IsPurchaseStore(int $storeType): bool {
        return (($storeType == StoreValue::TypeAppleIAP) ||
                ($storeType == StoreValue::TypeGoogleIAB) ||
                ($storeType == StoreValue::TypeMyCard));
    }

    public static function GetPurchasePlat(int $storeType): int {
        return match ($storeType) {
            StoreValue::TypeAppleIAP => StoreValue::PlatApple,
            StoreValue::TypeGoogleIAB => StoreValue::PlatGoogle,
            StoreValue::TypeMyCard => StoreValue::PlatMyCard,
            default => StoreValue::PlatNone
        };
    }

    public static function CheckAutoRefreshTime(int $updateTime): StoreRefreshTimeHolder {
        //商品更新,回傳剩餘時間
        return self::CheckTime($updateTime, ConfigGenerator::Instance()->StoreAutoRefreshTime);
    }

    public static function GetCurrencyItemID(int $currency): int {
        return match ($currency) {
            StoreValue::CurrencyMars => ItemValue::CurrencyCoin,
            StoreValue::CurrencyDiamond => ItemValue::CurrencyDiamond,
            StoreValue::CurrencyPT => ItemValue::CurrencyPetaToken,
            StoreValue::CurrencyMarsTicket => CompetitionsInfoHandler::Instance(RaceValue::LobbyCoinA)->GetInfo()->ticketId,
            StoreValue::CurrencyPtTicket => CompetitionsInfoHandler::Instance(RaceValue::LobbyPetaTokenA)->GetInfo()->ticketId
        // StoreValue::CurrencyGroupTicket => ConfigGenerator::Instance()->PvP_B_TicketId_3,
        };
    }

    public static function GetStoreInfosHolder(stdClass|null|false $row): StoreInfosHolder {
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

    public static function GetCallbackkey(int $device): string {
        return match ($device) {
            StoreValue::PlatGoogle => getenv(EnvVar::QuickSDKCallBackKeyAndroid),
            StoreValue::PlatApple => getenv(EnvVar::QuickSDKCallBackKeyiOS),
            default => ""
        };
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

    public static function GetSandBoxes(): array {
        $responseSanbox = [];
        $responseSanbox["mycard"] = getenv(EnvVar::MyCardSandboxmode) === "true" ? true : false;
        $responseSanbox["app"] = false;
        $responseSanbox["google"] = false;
        return $responseSanbox;
    }

    public static function GetCurrency(UserBagHandler $userBagHandler): array {
        $responseCurrencies = [];
        foreach (StoreValue::Currencies as $currency) {
            $itemID = StoreUtility::GetCurrencyItemID($currency);
            $responseCurrencies[] = $userBagHandler->GetItemAmount($itemID);
        }
        return $responseCurrencies;
    }

}
