<?php

namespace Games\Store;

use Accessors\CurlAccessor;
use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\Globals;
use Consts\HTTPCode;
use Games\Consts\StoreValue;
use Games\Exceptions\StoreException;
use Games\Store\Models\MyCardPaymentModel;
use Games\Store\Models\StoreProductInfoModel;
use Generators\DataGenerator;
use stdClass;

/*
 * Description of PurchaseUtility
 */

class MyCardUtility {

    public static function AuthGlobal(string $orderID, int $device, string $userID, StoreProductInfoModel|stdClass $productInfo, string $productName): string {

        if ($device == StoreValue::DeviceiOS) {
            $tradeType = 2;
            $facReturnURL = urldecode("http://localhost:37001/Store/MyCard/Buy/?orderID=5");
        } else {
            $tradeType = 1;
            $facReturnURL = "";
        }
        $requestData = new stdClass();
        $requestData->FacServiceId = getenv(EnvVar::MyCardFacserviceid);
        $requestData->FacTradeSeq = $orderID;
        $requestData->FacGameId = getenv(EnvVar::MyCardFacgameid);
        $requestData->FacGameName = getenv(EnvVar::MyCardFacgamename);
        $requestData->TradeType = $tradeType; //:Android SDK (手遊適用)
        $requestData->ServerId = "";
        $requestData->CustomerId = $userID;
        $requestData->PaymentType = "";
        $requestData->ItemCode = "";
        $requestData->ProductName = $productName;
        $requestData->Amount = $productInfo->Price;
        $requestData->Currency = $productInfo->ISOCurrency;
        $requestData->SandBoxMode = getenv(EnvVar::MyCardSandboxmode); //true/false
        $requestData->FacReturnURL = $facReturnURL;
        $requestData->FacKey = getenv(EnvVar::MyCardFackey);
        $requestData->hash = self::Hash($requestData);

        //$params = self::GetParams($requestData);

        $authCurl = new CurlAccessor(getenv(EnvVar::MyCardUri) . '/AuthGlobal');

        $curlReturn = $authCurl->ExecOptions([
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($requestData),
            CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded']
        ]);

        if ($curlReturn === false || $authCurl->GetInfo(CURLINFO_HTTP_CODE) != HTTPCode::OK) {
            throw new StoreException(StoreException::Error, ['[cause]' => 'L54']);
        }

        $queryData = json_decode($curlReturn);
        if ($queryData->ReturnCode == StoreValue::MyCardReturnSuccess) {
            return $queryData->AuthCode;
        } else {
            throw new StoreException(StoreException::Error, ['[cause]' => 'L61']);
        }
        return $queryData->AuthCode;
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

    public static function Verify(string $userID, string $authcode): int {

        $mainAccessor = new PDOAccessor(EnvVar::DBMain);
        $userInfo = $mainAccessor->SelectExpr('`UserID`, `CreateTime`')->FromTable('Users')->WhereEqual('UserID', $userID)->Fetch();
        if ($userInfo == false) {
            throw new StoreException(StoreException::Error);
        }

        $myCardPaymentModel = new MyCardPaymentModel();
        $myCardPaymentModel->CustomerId = $userID;
        $myCardPaymentModel->CreateAccountDateTime = $userInfo->CreateTime;
        $myCardPaymentModel->CreateAccountIP = "";

        $tradeCheck = MyCardUtility::TradeQuery($authcode, $myCardPaymentModel);
        if ($tradeCheck != StoreValue::PurchaseProcessSuccess) {
            return $tradeCheck;
        }

        $payCheck = MyCardUtility::PaymentConfirm($authcode, $myCardPaymentModel);
        if ($payCheck == StoreValue::PurchaseProcessSuccess) {

            $logAccessor = new PDOAccessor(EnvVar::DBLog);

            $row = $logAccessor->FromTable('MyCardPayment')->WhereEqual("FacTradeSeq", $myCardPaymentModel->FacTradeSeq)->Fetch();
            if (empty($row)) {

                $logAccessor->ClearCondition()->Add([
                    "PaymentType" => $myCardPaymentModel->PaymentType,
                    "TradeSeq" => $myCardPaymentModel->TradeSeq, //form PaymentConfirm
                    "MyCardTradeNo" => $myCardPaymentModel->MyCardTradeNo,
                    "FacTradeSeq" => $myCardPaymentModel->FacTradeSeq,
                    "CustomerId" => $userID,
                    "Amount" => $myCardPaymentModel->Amount,
                    "Currency" => $myCardPaymentModel->Currency,
                    "TradeDateTime" => (int) $GLOBALS[Globals::TIME_BEGIN],
                    "CreateAccountDateTime" => $userInfo->CreateTime,
                    "CreateAccountIP" => ""
                ]);
            }
        }
        return $payCheck;
    }

    private static function TradeQuery(string $authcode, MyCardPaymentModel &$myCardPaymentModel): int {

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
                $myCardPaymentModel->PaymentType = $queryData->PaymentType;
                $myCardPaymentModel->MyCardTradeNo = $queryData->MyCardTradeNo;
                $myCardPaymentModel->FacTradeSeq = $queryData->FacTradeSeq;
                $myCardPaymentModel->Amount = $queryData->Amount;
                $myCardPaymentModel->Currency = $queryData->Currency;
                //請款回傳

                return StoreValue::PurchaseProcessSuccess;
            } else {
                return StoreValue::PurchaseProcessFailure;
            }
        } else {//查詢失敗
            return StoreValue::PurchaseProcessFailure;
        }
    }

    private static function PaymentConfirm(string $authcode, MyCardPaymentModel &$myCardPaymentModel): int {

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
            $myCardPaymentModel->TradeSeq = $paymentData->TradeSeq;
            return StoreValue::PurchaseProcessSuccess;
        } else {//查詢失敗
            return StoreValue::PurchaseProcessFailure;
        }
    }

    public static function CheckAllowIP(): bool {
        $allowIPs = json_decode(getenv(EnvVar::MyCardAllowIp));
        $myIP = DataGenerator::UserIP();
        return in_array($myIP, $allowIPs);
    }

    public static function GetTimestring(int $timestamp): string {
        return DataGenerator::TimestringByTimezone($timestamp, 8, 'Y-m-d\TH:i:s');
    }

}
