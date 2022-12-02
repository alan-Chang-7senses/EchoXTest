<?php
namespace Processors\User;

use Consts\ErrorCode;
use Consts\Sessions;

use Games\Accessors\UserAccessor;
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
        $userAccessor = new UserAccessor();
        $row = $userAccessor->rowUserByID($userID);

        $tutorial = TutorialUtility::UpdateStep($row->Tutorial);

        if ($tutorial->nextStep != $row->Tutorial)
        {
            TutorialUtility::AddRewards($userID, $tutorial->rewardItems);

            $userAccessor->ModifyUserValuesByID($userID, ["Tutorial" => $tutorial->nextStep]);
            UserPool::Instance()->Delete($userID);
        }        
        
        $result = new ResultData(ErrorCode::Success);
        $result->tutorial = $tutorial->nextStep;
        $result->rewardItems = $tutorial->rewardItems;
       
        return $result;
    }

}
