<?php

namespace Processors;

use Accessors\CurlAccessor;
use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\Globals;
use Consts\HTTPCode;
use Exception;
use Helpers\InputHelper;
use Helpers\LogHelper;
use Holders\ResultData;
/**
 * Description of callback
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class callback extends BaseProcessor{
    
    protected bool $mustSigned = false;
    protected bool $resposeJson = false;

    public function Process(): ResultData {
        
        $code = InputHelper::get('code');
        $state = InputHelper::get('state');
        
        $curl = new CurlAccessor(getenv(EnvVar::SSOUri).'/oauth/token');
        $curlReturn = $curl->ExecOptions([
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query([
                'grant_type' => 'authorization_code',
                'client_id' => getenv(EnvVar::SSOClientID),
                'client_secret' => getenv(EnvVar::SSOClientSecret),
                'redirect_uri' => getenv(EnvVar::APPUri).'/callback',
                'code' => $code,
            ]),
            CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded']
        ]);
        
        if($curlReturn === false || $curl->GetInfo(CURLINFO_HTTP_CODE) != HTTPCode::OK)
        {
            LogHelper::Extra('TokenFail', ['curlReturn' => $curlReturn]);
            throw new Exception('Token fail ', ErrorCode::ProcessFailure);
        }
        
        $tokenData = json_decode($curlReturn);
        
        $curl = new CurlAccessor(getenv(EnvVar::SSOUri).'/oauth/userProfile');
        $curlReturn = $curl->ExecOptions([
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Authorization: Bearer '.$tokenData->access_token],
        ]);
        
        if($curlReturn === false || $curl->GetInfo(CURLINFO_HTTP_CODE) != HTTPCode::OK)
        {
            LogHelper::Extra('UserProfileFail', ['curlReturn' => $curlReturn]);
            throw new Exception('Get userProfile fail', ErrorCode::ProcessFailure);
        }
        
        $userProfile = json_decode($curlReturn);
        $accessor = new PDOAccessor(EnvVar::DBMain);
        $row = $accessor->FromTable('Users')->WhereEqual('Username', $userProfile->data->id)->Fetch();
        $accessor->ClearCondition();
        
        $uniwebviewMessage = 'LoginFinsh';
        if($row === false){
            
            $accessor->FromTable('Users')->Add([
                'Username' => $userProfile->data->id,
                'Email' => $userProfile->data->email,
                'CreateTime' => $GLOBALS[Globals::TIME_BEGIN]
            ]);
        
            $userID = $accessor->LastInsertID();
            $uniwebviewMessage = 'LoginFirst';
            
        }else{
            
            if(empty($row->Nickname)) $uniwebviewMessage = 'LoginFirst';
            $userID = $row->UserID;
        }
        
        $accessor->FromTable('Sessions')->WhereEqual('SessionID', $state)->Modify([
            'SessionData' => 'Signed|b:1;UserID|i:'.$userID.';',
            'UserID' => $userID
        ]);
        
        $result = new ResultData(ErrorCode::Success);
        $result->script = 'location.href = "uniwebview://'.$uniwebviewMessage.'?code='.ErrorCode::Success.'&message=";';
        $result->content = 'id: '.$userProfile->data->id.'<br>email: '.$userProfile->data->email;
        
        return $result;
    }
}
