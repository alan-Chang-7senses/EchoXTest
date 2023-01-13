<?php

namespace Games\Store;

use Consts\EnvVar;
use Google\Client;

/*
 * Description of PurchaseUtility
 */

class PurchaseUtility {

    public static function VerifyGoogle(string $subscriptionId, string $token) {

        $client = new Client();
        $client->setApplicationName(EnvVar::GoogleAppName); //This is the name of the linked application               

        $credentials = getcwd() . EnvVar::GoogleAppCredentials;
        $client->setAuthConfig($credentials);
        //$client->useApplicationDefaultCredentials();

        $apiKey = getenv(EnvVar::GoogleApiKey); //Your API key
        $client->setDeveloperKey($apiKey);

        $package_name = getenv(EnvVar::GooglePackagename); //Your package name (com.example...)              

        $service = new AndroidPublisher($client);
        $results = $service->purchases_subscriptions->get($package_name, $subscriptionId, $token, array());

        print_r($results); //This object has all the data about the subscription
        //成功加入購買 Log
        
        echo "expiration: " . $results->expiryTimeMillis;
    }

}
