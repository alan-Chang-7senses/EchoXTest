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
        $row = $pdo->FromTable("FreePetaProccess")
            ->WhereEqual("UserID",$_SESSION[Sessions::UserID])
            ->Fetch();

        $canSetNickname = true;
        $proccess = 0;
        if($row !== false)
        {            
            $canSetNickname = false;            
            $proccess = $row->Proccess;
            $freePetaIDs = $row->FreePetaIDs;
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
        $pdo->FromTable("FreePetaProccess")
            ->Add([
                "UserID" => $_SESSION[Sessions::UserID],
                "Proccess" => $proccess >= SetUserNicknameValue::HadNickname ? $proccess : SetUserNicknameValue::HadNickname,
                "FreePetaIDs" => empty($freePetaIDs) ? null : $freePetaIDs,
                ],true);
        $results = new ResultData(ErrorCode::Success);
        return $results;
    }
}
