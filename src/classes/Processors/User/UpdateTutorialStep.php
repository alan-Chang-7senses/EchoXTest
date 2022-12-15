<?php
namespace Processors\User;

use Consts\ErrorCode;
use Consts\Sessions;

use Games\Accessors\UserAccessor;
use Games\Accessors\AccessorFactory;
use Games\Users\UserHandler;
use Games\Users\TutorialUtility;
use Games\Pools\UserPool;

use Holders\ResultData;
use Processors\BaseProcessor;


/**
 * Description of SetTutorialStep
 *
 * @author Lin Zheng Fu <sigma@7senses.com>
 */
class UpdateTutorialStep extends BaseProcessor{
    
    
    public function Process(): ResultData {

        $userID = $_SESSION[Sessions::UserID];
        $userInfo = (new UserHandler($userID))->GetInfo();

        $tutorial = TutorialUtility::UpdateStep($userInfo->tutorial);

        if ($tutorial->nextStep != $userInfo->tutorial)
        {
            TutorialUtility::AddRewards($userID, $tutorial->rewardItems);

            $nextStep = $tutorial->nextStep;

            $accessor = AccessorFactory::Main();
            
            $accessor->Transaction(function () use ($accessor, $userID, $nextStep) {
                $row = $accessor->FromTable('Users')->WhereEqual('UserID', $userID)->ForUpdate()->Fetch();
                if($row === false)return; //角色不在User表
                $accessor->Modify(['Tutorial' => $nextStep]);
                UserPool::Instance()->Delete($userID);
            });
        }        
        
        $result = new ResultData(ErrorCode::Success);
        $result->tutorial = $tutorial->nextStep;
        $result->rewardItems = $tutorial->rewardItems;
       
        return $result;
    }

}
