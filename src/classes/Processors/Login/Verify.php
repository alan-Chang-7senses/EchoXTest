<?php

namespace Processors\Login;

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
class Verify extends BaseProcessor{
    
    
    public function __construct() {
        $this->mustSigned = false;
        parent::__construct();
    }
    
    public function Process(): ResultData {
        
        $account = InputHelper::post('account');
        $password = InputHelper::post('password');
        
        if(!preg_match(Predefined::FormatAccount, $account) || !preg_match(Predefined::FormatPassword, $password))
            throw new LoginException (LoginException::FormatError);
       
        $row = (new UserAccessor())->rowUserByUsername($account);
        
        if($row === false) throw new LoginException(LoginException::NoAccount);
        if($row->Status != Predefined::UserEnabled) throw new LoginException(LoginException::DisabledAccount);
        if(!password_verify($password, $row->Password)) throw new LoginException(LoginException::PasswordError);

        $_SESSION[Sessions::Signed] = true;
        $_SESSION[Sessions::UserID] = $row->UserID;
        
        $userInfo = new stdClass();
        $userInfo->userID = $row->UserID;
        $userInfo->nickname = $row->Nickname;
        $userInfo->level = $row->Level;
        $userInfo->exp = $row->Exp;
        $userInfo->vitality = $row->Vitality;
        $userInfo->money = $row->Money;
        
        $result = new ResultData(ErrorCode::Success);
        $result->userInfo = $userInfo;
        
        return $result;
    }
}
