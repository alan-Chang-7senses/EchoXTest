<?php
namespace Processors\User;

use Consts\ErrorCode;
use Consts\Predefined;
use Consts\Sessions;
use Exceptions\LoginException;
use Games\Accessors\UserAccessor;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\BaseProcessor;
use stdClass;
/**
 * Description of Login
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class Login extends BaseProcessor{
    
    protected bool $mustSigned = false;
    
    public function Process(): ResultData {
        
        $account = InputHelper::post('account');
        $password = InputHelper::post('password');
        
        if(!preg_match(Predefined::FormatAccount, $account) || !preg_match(Predefined::FormatPassword, $password))
            throw new LoginException (LoginException::FormatError);
        
        $userAccessor = new UserAccessor();
        $row = $userAccessor->rowUserByUsername($account);
        
        if($row === false) throw new LoginException(LoginException::NoAccount);
        if($row->Status != Predefined::UserEnabled) throw new LoginException(LoginException::DisabledAccount);
        if(!password_verify($password, $row->Password)) throw new LoginException(LoginException::PasswordError);
        
        $rowSession = $userAccessor->rowSessionByID(session_id());
        if(!empty($rowSession)) $userAccessor->DeleteUserSessionByEarlierTime($row->UserID, $rowSession->SessionExpires);
        
        $_SESSION[Sessions::Signed] = true;
        $_SESSION[Sessions::UserID] = $row->UserID;
        
        $userInfo = new stdClass();
        $userInfo->userID = $row->UserID;
        $userInfo->nickname = $row->Nickname;
        $userInfo->level = $row->Level;
        $userInfo->exp = $row->Exp;
        $userInfo->vitality = $row->Vitality;
        $userInfo->money = $row->Money;
        $userInfo->player = $row->Player;
        $userInfo->race = $row->Race;
        
        $result = new ResultData(ErrorCode::Success);
        $result->userInfo = $userInfo;
        
        return $result;
    }

}
