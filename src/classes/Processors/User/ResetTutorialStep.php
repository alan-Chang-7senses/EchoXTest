<?php
namespace Processors\User;

use Consts\ErrorCode;
use Consts\Sessions;

use Consts\EnvVar;
use Consts\Predefined;

use Games\Accessors\UserAccessor;
use Games\Users\UserHandler;
use Games\Pools\UserPool;

use Holders\ResultData;
use Processors\BaseProcessor;


/**
 * Description of SetTutorialStep
 *
 * @author Lin Zheng Fu <sigma@7senses.com>
 */
class ResetTutorialStep extends BaseProcessor{
    
    
    public function Process(): ResultData {

        $userID = $_SESSION[Sessions::UserID];
        $userInfo = (new UserHandler($userID))->GetInfo();

        $targetStep = 1;

        if (1 != $userInfo->tutorial && $this->IsTestEnv())
        {
            $userAccessor = new UserAccessor();
            $userAccessor->ModifyUserValuesByID($userID, ["Tutorial" => $targetStep]);
            UserPool::Instance()->Delete($userID);
        } 
        else
        {
            $targetStep = $userInfo->tutorial;
        }       
        
        $result = new ResultData(ErrorCode::Success);
        $result->tutorial = $targetStep;
       
        return $result;
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
