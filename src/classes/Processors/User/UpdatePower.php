<?php

namespace Processors\User;

use Consts\ErrorCode;
use Consts\Sessions;
use Games\Accessors\UserAccessor;
use Games\Users\APRecoverUtility;
use Games\Users\UserHandler;
use Holders\ResultData;
use Processors\BaseProcessor;

class UpdatePower extends BaseProcessor
{
    public function Process(): ResultData 
    {
        $userID = $_SESSION[Sessions::UserID];
        $userHandler = new UserHandler($userID);

        $row = (new UserAccessor())->rowPowerByID($userID);
        $lastUpdateTime = $row === false ? 0 : $row->PowerUpdateTime;
        $userHandler->HandlePower(0);
        $time =  APRecoverUtility::GetFullAPTime($userID,$lastUpdateTime);
        $rate = APRecoverUtility::GetMaxAPAmountAndRecoverRate($userID)->rate;
        
        $results = new ResultData(ErrorCode::Success);
        $results->power = $userHandler->GetInfo()->power;        
        $results->timeTillFull = $time;
        $results->rate = $rate;
        return $results;        
    }
}