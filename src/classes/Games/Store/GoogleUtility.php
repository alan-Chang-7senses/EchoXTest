<?php

namespace Games\Store;

use Consts\EnvVar;
use Games\Consts\StoreValue;
use Games\Store\Holders\GoogleInfoHolder;
use Google\Auth\Credentials\ServiceAccountCredentials;
use Google\Client;
use Google\Service\AndroidPublisher;
use Google\Service\AndroidPublisher\ProductPurchasesAcknowledgeRequest;
use stdClass;
use function GuzzleHttp\json_encode;

/**
 *  Google 相關
 * 儲值
 */
class GoogleUtility {

    private static function GetToken() {

        $credentials = new ServiceAccountCredentials(
                array('https://www.googleapis.com/auth/androidpublisher'),
                getcwd() . getenv(EnvVar::GoogleServiceAccount)
        );
        $token = $credentials->fetchAuthToken();
        //$token->creat = (int) $GLOBALS[Globals::TIME_BEGIN];
        
        return $token;
    }

    private static function GetService(): AndroidPublisher {

        $client = new Client();
        $client->setApplicationName(getenv(EnvVar::GoogleAppName));
        $client->setAuthConfig(getcwd() . getenv(EnvVar::GoogleAppCredentials));
        $client->setScopes('https://www.googleapis.com/auth/androidpublisher');
        $client->setDeveloperKey(getenv(EnvVar::GoogleApiKey));
        $client->setAccessToken(self::GetToken());

        
        $tokenPath = getcwd()."/configs/token.json";
        $client->setAccessType('offline');
        $client->setPrompt('consent');
        if ($client->isAccessTokenExpired()) {
            // Refresh the token if possible, else fetch a new one.
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {
                $client->setAccessToken(self::GetToken());
            }
            if (!file_exists(dirname($tokenPath))) {
                mkdir(dirname($tokenPath), 0700, true);
            }
            file_put_contents($tokenPath, json_encode($client->getAccessToken()));
        }

        return new AndroidPublisher($client);
    }

    /**
     * 取得商品資訊
     * @param string $sku
     * @param string $token
     * @return GoogleInfoHolder
     */
    public static function GetInfo(string $sku, string $token): GoogleInfoHolder {
        $service = Self::GetService();
        $package_name = getenv(EnvVar::GooglePackagename);
        $results = $service->purchases_products->get($package_name, $sku, $token);

        $info = new GoogleInfoHolder();
        $info->kind = $results->kind;
        $info->purchaseTimeMillis = $results->purchaseTimeMillis;
        $info->purchaseState = $results->purchaseState;
        $info->consumptionState = $results->consumptionState;
        $info->developerPayload = $results->developerPayload;
        $info->orderId = $results->orderId;
        $info->purchaseType = $results->purchaseType;
        $info->acknowledgementState = $results->acknowledgementState;
        $info->purchaseToken = $results->purchaseToken;
        $info->productId = $results->productId;
        $info->quantity = $results->quantity;
        $info->obfuscatedExternalAccountId = $results->obfuscatedExternalAccountId;
        $info->obfuscatedExternalProfileId = $results->obfuscatedExternalProfileId;
        $info->regionCode = $results->regionCode;

        return $info;
    }

    /**
     * 驗證並回復商品取得
     * @param string $subscriptionId
     * @param string $token
     */
    public static function Verify(string $subscriptionId, string $token): stdClass {

        $service = Self::GetService();
        $package_name = getenv(EnvVar::GooglePackagename);

        $postBody = new ProductPurchasesAcknowledgeRequest();
        $postBody->setDeveloperPayload("");

        //$results = $service->purchases_products->acknowledge($package_name, $subscriptionId, $token, $postBody);
        //如果成功，則回應內容為空白
        //成功加入購買 Log

        $result = new \stdClass;
        $result->code = StoreValue::PurchaseVerifySuccess;
        $result->message = "pay finish";

        return $result;
    }

}
