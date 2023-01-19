<?php

namespace Games\Store;

use Consts\EnvVar;
use Consts\Globals;
use Games\Consts\StoreValue;
use Google\Auth\Credentials\ServiceAccountCredentials;
use Google\Client;
use Google\Service\AndroidPublisher;
use Google\Service\AndroidPublisher\ProductPurchase;
use Google\Service\AndroidPublisher\ProductPurchasesAcknowledgeRequest;
use stdClass;
use Throwable;
use function GuzzleHttp\json_decode;
use function GuzzleHttp\json_encode;

/**
 * Google 相關
 * 1.儲值 2.
 */
class GoogleUtility {

    private static function GetToken() {

        $credentials = new ServiceAccountCredentials(
                array('https://www.googleapis.com/auth/androidpublisher'),
                getcwd() . getenv(EnvVar::GoogleServiceAccount)
        );
        $token = $credentials->fetchAuthToken();
        $token['created'] = $GLOBALS[Globals::TIME_BEGIN];
        return $token;
    }

    private static function GetService(): AndroidPublisher {
        $tokenPath = getcwd() . "/configs/token.json";

        $client = new Client();
        $client->setApplicationName(getenv(EnvVar::GoogleAppName));
        $client->setAuthConfig(getcwd() . getenv(EnvVar::GoogleAppCredentials));
        $client->setScopes('https://www.googleapis.com/auth/androidpublisher');
        $client->setDeveloperKey(getenv(EnvVar::GoogleApiKey));

        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $client->setAccessToken($accessToken);
        }

        $client->setAccessType('offline');
        $client->setPrompt('consent');
        if ($client->isAccessTokenExpired()) {
            $token = self::GetToken();
            $client->setAccessToken($token);
            if (!file_exists(dirname($tokenPath))) {
                mkdir(dirname($tokenPath), 0700, true);
            }
            file_put_contents($tokenPath, json_encode($token));
        }
        return new AndroidPublisher($client);
    }

    /**
     * 取得商品資訊
     * @param string $sku
     * @param string $token
     * @return ProductPurchase
     */
    public static function GetInfo(string $sku, string $token): ProductPurchase {
        $service = Self::GetService();
        $package_name = getenv(EnvVar::GooglePackagename);
        $results = $service->purchases_products->get($package_name, $sku, $token);
        return $results;
    }

    /**
     * 驗證並回復商品取得
     * @param string $sku
     * @param string $token
     */
    public static function Verify(string $sku, string $token): stdClass {

        try {
            $service = Self::GetService();
            $package_name = getenv(EnvVar::GooglePackagename);
            $postBody = new ProductPurchasesAcknowledgeRequest(['developerPayload' => ""]);
            $response = $service->purchases_products->acknowledge($package_name, $sku, $token, $postBody);

            //如果成功，則回應內容為空白      
            if (empty((array) $response)) {
                $result = new stdClass();
                $result->code = StoreValue::PurchaseVerifySuccess;
                $result->message = "pay finish";
                return $result;
            } else {
                $result = new stdClass();
                $result->code = StoreValue::PurchaseVerifyFailure;
                $result->message = json_encode($response);
                return $result;
            }
        } catch (Throwable $ex) {
            $result = new stdClass();
            $result->code = StoreValue::PurchaseVerifyFailure;
            $error = json_decode($ex->getMessage());
            $result->message = $error->error->message;
            return $result;
        }
    }

}
