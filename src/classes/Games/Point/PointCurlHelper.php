<?php

namespace Games\Point;

use Accessors\CurlAccessor;
use Consts\EnvVar;
use Consts\Globals;

class PointCurlHelper
{
    const POST = 'POST';
    const GET = 'GET';
    private string $type;
    private string $url;
    // private array $signatureData;
    private array $queryStringParams = [];
    private array $bodyParams = [];

    public function __construct(string $requestType,  string $url)
    {
        $this->type = strtoupper($requestType);
        $this->url = $url;
    }

    public function AddBodyParams($key, $value) : void
    {
        $this->bodyParams[$key] = $value;
    }
    public function AddQueryStringParams($key, $value) : void
    {
        $this->queryStringParams[$key] = $value;
    }

    private function BuildSignature() : string
    {
        $baseSignatureData = 
        [
            'clientId' => getenv(EnvVar::PTPOINT_CLIENTID),
            'path' => $this->url,
            'secret' => getenv(EnvVar::PTPOINT_SECRET),
            'timestamp' => intval($GLOBALS[Globals::TIME_BEGIN]),
        ];
        $signatureData = array_merge($baseSignatureData,$this->queryStringParams,$this->bodyParams);
        ksort($signatureData);
        return hash('sha256',http_build_query($signatureData));
    }

    public function GetResponse() : mixed
    {
        $queryString = http_build_query($this->queryStringParams);
        if(!empty($queryString)) $queryString = '?' . $queryString;
        $url = getenv(EnvVar::PTPOINT_URI) . $this->url . $queryString;
        $curl = new CurlAccessor($url);
        $execOptions = 
        [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => $this->type == self::POST,
            CURLOPT_HTTPHEADER => 
            [
                'X-CLIENT-ID: ' . getenv(EnvVar::PTPOINT_CLIENTID),
                'X-SIGNATURE: ' . $this->BuildSignature(),
                'X-TIMESTAMP: ' . intval($GLOBALS[Globals::TIME_BEGIN]),
            ],
        ];
        if(!empty($this->bodyParams))
        {
            $execOptions[CURLOPT_POSTFIELDS] = $this->bodyParams;
        }

        return $curl->ExecOptions($execOptions);
    }
}