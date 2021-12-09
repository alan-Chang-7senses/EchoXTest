<?php

namespace Processors\Login;

use Accessors\PDOAccessor;
use Consts\ErrorCode;
use Consts\Predefined;
use Consts\Sessions;
use Exceptions\LoginException;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\BaseProcessor;
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
        
        $row = (new PDOAccessor('KoaMain'))->FromTable('Users')->WhereEqual('Username', $account)->Fetch();
        
        if($row === false) throw new LoginException(LoginException::NoAccount);
        if($row->Status != Predefined::UserEnabled) throw new LoginException(LoginException::DisabledAccount);
        if(!password_verify($password, $row->Password)) throw new LoginException(LoginException::PasswordError);

        $_SESSION[Sessions::Signed] = true;
        $_SESSION[Sessions::UserID] = $row->UserID;

        unset($row->Password);
        
        $result = new ResultData(ErrorCode::Success);
        $result->userInfo = $row;
        
        return $result;
    }
}
