<?php

namespace Games\Store;

use Accessors\CurlAccessor;
use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\Globals;
use Consts\HTTPCode;
use Games\Consts\StoreValue;
use Helpers\LogHelper;

/*
 * Description of PurchaseUtility
 */

class GoogleUtility {

   
//    public static function GoogleVerify(string $subscriptionId, string $token) {
//        $client = new Client();
//        $client->setApplicationName(EnvVar::GoogleAppName); //This is the name of the linked application
//        $client->useApplicationDefaultCredentials();
//
//        $apiKey = getenv(EnvVar::GoogleApiKey); //Your API key
//        $client->setDeveloperKey($apiKey);
//
//        $package_name = getenv(EnvVar::GooglePackagename); //Your package name (com.example...)              
//
//        $service = new AndroidPublisher($client);
//        $results = $service->purchases_subscriptions->get($package_name, $subscriptionId, $token, array());
//
//        print_r($results); //This object has all the data about the subscription
//        echo "expiration: " . $results->expiryTimeMillis;
//    }
}