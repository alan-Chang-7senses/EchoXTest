<?php

namespace Games\Store;

use Accessors\CurlAccessor;
use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\Globals;
use Consts\HTTPCode;
use Games\Consts\StoreValue;
use Games\Exceptions\StoreException;
use Games\Store\Holders\StoreProductInfoHolder;
use stdClass;

/*
 * Description of PurchaseUtility
 */

class MyCardUtility {

    public static function AuthGlobal(string $orderID, string $userID, StoreProductInfoHolder|stdClass $productInfo): string {

        $data = new stdClass();
        $data->FacServiceId = getenv(EnvVar::MyCardFacserviceid);
        $data->FacTradeSeq = $orderID;
        $data->FacGameId = getenv(EnvVar::MyCardFacgameid);
        $data->FacGameName = getenv(EnvVar::MyCardFacgamename);
        $data->TradeType = "1"; //:Android SDK (手遊適用)
        $data->ServerId = "";
        $data->CustomerId = $userID;
        $data->PaymentType = "";
        $data->ItemCode = "";
        $data->ProductName = $productInfo->productName;
        $data->Amount = $productInfo->amount;
        $data->Currency = $productInfo->currency;
        $data->SandBoxMode = getenv(EnvVar::MyCardSandboxmode); //true/false
        $data->FacReturnURL = "";
        $data->FacKey = getenv(EnvVar::MyCardFackey);
        $data->hash = self::Hash($data);

        $params = self::GetParams($data);

        $authCurl = new CurlAccessor(getenv(EnvVar::MyCardUri) . '/AuthGlobal');

        $curlReturn = $authCurl->ExecOptions([
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($data),
            CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded']
        ]);

        if ($curlReturn === false || $authCurl->GetInfo(CURLINFO_HTTP_CODE) != HTTPCode::OK) {
            throw new StoreException(StoreException::Error);
        }

        $queryData = json_decode($curlReturn);
        if ($queryData->ReturnCode == StoreValue::MyCardReturnSuccess) {
            return $queryData->AuthCode;
        } else {
            throw new StoreException(StoreException::Error);
        }
        return "TestAuthcode";
    }

    private static function Hash(stdclass $data): string {
        $preHashValue = "";
        foreach ($data as $key) {
            $preHashValue .= $key;
        }
        $urlcode = urlencode($preHashValue);
        $encodeHashValue = strtolower($urlcode);
        return hash("sha256", $encodeHashValue);
    }

    private static function GetParams(stdclass $data): array {

        $result = [];
        foreach ($data as $key => $value) {
            if ($value == "") {
                continue;
            }
            $result[$key] = $value;
        }
        return $result;
    }

    public static function TradeQuery(string $userID, string $authcode): int {

        $verifyCurl = new CurlAccessor(getenv(EnvVar::MyCardUri) . '/TradeQuery');
        $curlReturn = $verifyCurl->ExecOptions([
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query([
                'authcode' => $authcode,
            ]),
            CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded']
        ]);

        if ($curlReturn === false || $verifyCurl->GetInfo(CURLINFO_HTTP_CODE) != HTTPCode::OK) {
            return StoreValue::PurchaseProcessRetry; //連不到網路
        }

        $queryData = json_decode($curlReturn);
        if ($queryData->ReturnCode == StoreValue::MyCardReturnSuccess) {
            if ($queryData->PayResult == StoreValue::MyCardPaySuccess) {

                $accessor = new PDOAccessor(EnvVar::DBLog);
                $accessor->FromTable('StorePurchaseOrders')->Add([
                    "OrderID" => $queryData->FacTradeSeq,
                    "UserID" => $userID,
                    "PaymentType" => $queryData->PaymentType,
                    "Amount" => $queryData->Amount,
                    "Currency" => $queryData->Currency,
                    "MyCardTradeNo" => $queryData->MyCardTradeNo,
                    "MyCardType" => $queryData->MyCardType,
                    "PromoCode" => $queryData->PromoCode,
                    "SerialId" => $queryData->SerialId,
                    "CreateTime" => (int) $GLOBALS[Globals::TIME_BEGIN],
                ]);
                return StoreValue::PurchaseProcessSuccess;
            } else {
                return StoreValue::PurchaseProcessFailure;
            }
        } else {//查詢失敗
            return StoreValue::PurchaseProcessFailure;
        }
    }

    public static function PaymentConfirm(string $authcode): int {

        $paymentCurl = new CurlAccessor(getenv(EnvVar::MyCardUri) . '/PaymentConfirm');
        $paymentReturn = $paymentCurl->ExecOptions([
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query([
                'authcode' => $authcode,
            ]),
            CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded']
        ]);

        if ($paymentReturn === false || $paymentCurl->GetInfo(CURLINFO_HTTP_CODE) != HTTPCode::OK) {
            return StoreValue::PurchaseProcessRetry; //連不到網路
        }

        $paymentData = json_decode($paymentReturn);

        if ($paymentData->ReturnCode == StoreValue::MyCardReturnSuccess) {
            //付費紀錄
            return StoreValue::PurchaseProcessSuccess;
        } else {//查詢失敗
            return StoreValue::PurchaseProcessFailure;
        }
    }

}
