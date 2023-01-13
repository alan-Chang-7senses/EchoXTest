<?php

namespace Processors;

use Accessors\CurlAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\Globals;
use Consts\HTTPCode;
use Consts\ResposeType;
use Consts\Sessions;
use Exception;
use Games\Accessors\AccessorFactory;
use Games\Consts\ItemValue;
use Games\Consts\PointQueryValue;
use Games\Mails\MailsHandler;
use Games\Point\UserPoint;
use Games\Pools\UserPool;
use Games\Users\UserUtility;
use Generators\ConfigGenerator;
use Generators\DataGenerator;
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
    protected int $resposeType = ResposeType::UniWebView;

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
        $accessor = AccessorFactory::Main();
        
        $row = $accessor->FromTable('Sessions')->WhereEqual('SessionID', $state)->Fetch();
        session_decode($row->SessionData ?? '');
        $userIP = $_SESSION[Sessions::UserIP] ?? '';
        
        $row = $accessor->ClearCondition()->FromTable('Users')->WhereEqual('Username', $userProfile->data->id)->Fetch();
        
        $accessor->ClearCondition();
        
        $uniwebviewMessage = 'LoginFinish';
        $currentTime = $GLOBALS[Globals::TIME_BEGIN];
        if($row === false){
            
            $res = $accessor->FromTable('Users')->Add([
                'Username' => $userProfile->data->id,
                'Nickname' => $userProfile->data->id,
                'Email' => $userProfile->data->email,
                'CreateTime' => $currentTime,
                'CreatedIP' => $userIP
            ]);
            
            $userID = $accessor->LastInsertID();
            $uniwebviewMessage = 'LoginFirst';
            
            if($res){
                
                $config = ConfigGenerator::Instance();
                
                $items = json_decode($config->CreateUserItems ?? '[]');
                if(!empty($items)) UserUtility::AddItems($userID, $items, ItemValue::CauseCreateUser);
                
                $mailIDs = json_decode($config->CreateUserMailIDs ?? '[]');
                $mailDay = $config->CreateUserMailDay;
                $mailsHandler = new MailsHandler();
                foreach($mailIDs as $mailID) $mailsHandler->AddMail($userID, $mailID, $mailDay);
            }
            
        }else{
            
            if($row->Nickname == $row->Username) $uniwebviewMessage = 'LoginFirst';
            $userID = $row->UserID;
        }
        
        $_SESSION[Sessions::UserID] = $userID;
        $_SESSION[Sessions::Signed] = true;
        
        $accessor->FromTable('Sessions')->WhereEqual('SessionID', $state)->Modify([
            'SessionData' => session_encode(),
            'UserID' => $userID
        ]);
        
        session_gc();
        session_destroy();
        
        $accessor->ClearCondition();
        $rows = $accessor->FromTable('Sessions')->WhereEqual('UserID', $userID)->FetchAll();
        $sessionIDs = [];
        foreach($rows as $row){
            if($row->SessionID == $state) continue;
            $sessionIDs[] = $row->SessionID;
        }
        
        $accessor->ClearCondition();
        $accessor->FromTable('Sessions')->WhereIn('SessionID', $sessionIDs)->Delete();
        
        $accessor->ClearCondition();
        $row = $accessor->FromTable('UserRetainPoints')->WhereEqual('UserID', $userID)->Fetch();
        if($row === false) $accessor->Add(['UserID' => $userID, 'UpdateTime' => $currentTime]);
        else{
            
            $timezone = getenv(EnvVar::TimezoneDefault);
            $yesterday = DataGenerator::TimestampByTimezone('yesterday', $timezone);
            $today = DataGenerator::TimestampByTimezone('today', $timezone);
            if($row->UpdateTime >= $yesterday && $row->UpdateTime < $today){
                
                $accessor->Modify([
                    'Points' => $row->Points + 1,
                    'UpdateTime' => $currentTime,
                ]);
            }
        }
        
        $accessor = AccessorFactory::Log();
        $accessor->FromTable('UserLogin')->Add([
            'UserID' => $userID,
            'UserIP' => $userIP,
            'LogTime' => $currentTime
        ]);
        
        $result = new ResultData(ErrorCode::Success);
        $result->script = 'location.href = "uniwebview://'.$uniwebviewMessage.'?code='.ErrorCode::Success.'&message=";';
        $result->content = 'id: '.$userProfile->data->id.'<br>email: '.$userProfile->data->email;
        
        $ptPoint = (new UserPoint($userID,$userProfile->data->id))->GetPoint(PointQueryValue::SymbolPT);
        if($ptPoint !== false)
        {
            AccessorFactory::Main()->FromTable('Users')->WhereEqual('UserID',$userID)->Modify(['PetaToken' => $ptPoint * PointQueryValue::MultiplierPT]);
            UserPool::Instance()->Delete($userID);
        }
        return $result;
    }
}
