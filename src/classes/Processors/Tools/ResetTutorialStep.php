<?php
namespace Processors\Tools;

use Consts\ErrorCode;

use Consts\EnvVar;
use Consts\Predefined;

use Games\Accessors\UserAccessor;
use Games\Users\UserHandler;
use Games\Pools\UserPool;

use Holders\ResultData;
use Helpers\InputHelper;

use Exception;


/**
 * Description of SetTutorialStep
 *
 * @author Lin Zheng Fu <sigma@7senses.com>
 */
class ResetTutorialStep extends BaseTools{
    
    
    public function Process(): ResultData {

        if($this->IsTestEnv() == false) throw new Exception ('You do not have this permission', ErrorCode::VerifyError);

        $users = json_decode(InputHelper::post('users'));
        if($users === null) throw new Exception ('illegel parameter of users', ErrorCode::VerifyError);

        $log = [];
        foreach($users as $user)
        {
            $log[$user] = $this->ResetTutorial($user);
        }
        
        $result = new ResultData(ErrorCode::Success);
        $result->history = $log;
       
        return $result;
    }

    protected function ResetTutorial(int $userID): int
    {
        $userInfo = (new UserHandler($userID))->GetInfo();

        $targetStep = 1;

        if ($targetStep != $userInfo->tutorial)
        {
            $userAccessor = new UserAccessor();
            $userAccessor->ModifyUserValuesByID($userID, ["Tutorial" => $targetStep]);
            UserPool::Instance()->Delete($userID);
        } 
        else
        {
            $targetStep = $userInfo->tutorial;
        }   
        
        return $targetStep;
    }

    protected function IsTestEnv(): bool
    {
        $testEnv = [
            Predefined::SysLocal,
            'beta'
        ];
        return in_array(getenv(EnvVar::SysEnv), $testEnv);
    }

}
