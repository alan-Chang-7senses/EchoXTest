<?php
namespace Processors\User;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\Sessions;
use Consts\SetUserNicknameValue;
use Error;
use Games\Accessors\UserAccessor;
use Games\Exceptions\UserException;
use Games\Users\NamingUtility;
use Games\Users\UserHandler;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\BaseProcessor;

class SetUserNickname extends BaseProcessor{
    
    public function Process(): ResultData
    {        
        // 傳入暱稱字串
        // 不回傳參數
        $nickname = InputHelper::post("nickname");
        if(!NamingUtility::IsOnlyEnglishAndNumber($nickname))throw new UserException(UserException::UsernameNotEnglishOrNumber);        
        if(NamingUtility::ValidateLength($nickname,SetUserNicknameValue::MaxLenght)) throw new UserException(UserException::UsernameTooLong);
        
        $bandedWords = NamingUtility::GetBandedWordEnglish();
        if(NamingUtility::HasBandedWord($nickname,$bandedWords))throw new UserException(UserException::UsernameDirty,['username' => $nickname]);
        
        $pdo = new PDOAccessor(EnvVar::DBMain);
        $row = $pdo->FromTable("FreePetaProcess")
            ->WhereEqual("UserID",$_SESSION[Sessions::UserID])
            ->Fetch();

        if($row === false)throw new UserException(UserException::CanNotResetName);    
        $canSetNickname = true;
        // $process = 0;
        $process = $row->Process;
        if($process < SetUserNicknameValue::HadFreePeta) throw new UserException(UserException::CanNotResetName);    
        if($process >= SetUserNicknameValue::HadNickname)
        {            
            $canSetNickname = false;            
            // if(/*檢查是否能夠重設暱稱，與暱稱是否與原先相同*/)
            // {
            //         $canSetNickname = true;
            // }                        
        }
        if(!$canSetNickname) throw new UserException(UserException::CanNotResetName);
        
        $isAlreayExist = NamingUtility::IsNameAlreadyExist($nickname,EnvVar::DBMain,"Users","Nickname");
        if($isAlreayExist)throw new UserException(UserException::UsernameAlreadyExist,['username' => $nickname]);                 
        
        $userHandler = new UserHandler($_SESSION[Sessions::UserID]);
        $userHandler->SaveData(["Nickname" => $nickname]);
        //TODO：成功時要更新玩家在創角流程的進度
        $pdo->ClearAll();
        $pdo->FromTable("FreePetaProcess")
            ->WhereEqual("UserID",$_SESSION[Sessions::UserID])
            ->Modify([
                "Process" => $process >= SetUserNicknameValue::HadNickname ? $process : SetUserNicknameValue::HadNickname,
                ]);
        //TODO：將持有的免費peta寫入DB
        $results = new ResultData(ErrorCode::Success);
        return $results;
    }
}
