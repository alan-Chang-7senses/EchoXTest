<?php

namespace Processors\User;

use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\Sessions;
use Generators\DataGenerator;
use Holders\ResultData;
use Processors\BaseProcessor;
/**
 * Description of Auth
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class SSOAuthURL extends BaseProcessor{
    
    protected bool $mustSigned = false;
    
    public function Process(): ResultData {
        
        $params['client_id'] = getenv(EnvVar::SSOClientID);        
        // $params['client_secret'] = getenv(EnvVar::ClientSecret);
        $params['redirect_uri'] = getenv(EnvVar::APPUri).'/callback';
        $params['response_type'] = 'code';
        $params['scope'] = '' ;
        $params['state'] = session_id();
        
        $_SESSION[Sessions::UserIP] = DataGenerator::UserIP();

        $result = new ResultData(ErrorCode::Success);
        $result->url = urldecode(getenv(EnvVar::SSOUri).'/oauth/authorize?'.http_build_query($params));

        return $result;
    }
}
