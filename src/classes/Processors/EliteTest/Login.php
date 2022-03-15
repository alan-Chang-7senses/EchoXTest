<?php
namespace Processors\EliteTest;

use Consts\ErrorCode;
use Consts\Predefined;
use Consts\Sessions;
use Exceptions\LoginException;
use Games\Accessors\EliteTestAccessor;
use Games\Accessors\UserAccessor;
use Games\EliteTest\EliteTestValues;
use Generators\ConfigGenerator;
use Generators\DataGenerator;
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
        
        $eliteTestAccessor = new EliteTestAccessor();
        $row = $eliteTestAccessor->rowUserByUsername($account);
        
        if($row === false) throw new LoginException(LoginException::NoAccount);
        if($row->Status != Predefined::UserEnabled) throw new LoginException(LoginException::DisabledAccount);
        if(!password_verify($password, $row->Password)) throw new LoginException(LoginException::PasswordError);
        
        $userID = $row->UserID + EliteTestValues::BaseUserID;
        (new UserAccessor())->DeleteUserSessionByUserId($userID);
        
        $_SESSION[Sessions::Signed] = true;
        $_SESSION[Sessions::UserID] = $userID;
        
        $eliteTestAccessor->IncreaseTotalLoginHours();
        $eliteTestAccessor->AddUserLogin($row->UserID);
        
        $result = new ResultData(ErrorCode::Success);
        $result->info = [
            'id' => $row->UserID,
            'race' => $row->Race,
            'score' => $row->Score,
        ];
        
        return $result;
    }
}
