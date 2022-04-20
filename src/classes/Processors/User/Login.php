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
        
        $userAccessor->DeleteUserSessionByUserId($row->UserID);
        
        $_SESSION[Sessions::Signed] = true;
        $_SESSION[Sessions::UserID] = $row->UserID;
        
        $result = new ResultData(ErrorCode::Success);
        $result->userInfo = [
            'userID' => $row->UserID,
            'nickname' => $row->Nickname,
            'level' => $row->Level,
            'exp' => $row->Exp,
            'vitality' => $row->Vitality,
            'money' => $row->Money,
            'player' => $row->Player,
            'scene' => $row->Scene,
            'race' => $row->Race,
        ];
        
        return $result;
    }

}
