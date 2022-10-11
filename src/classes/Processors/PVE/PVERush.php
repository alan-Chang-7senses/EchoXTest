<?php

namespace Processors\PVE;

use Consts\ErrorCode;
use Consts\Sessions;
use Games\Consts\ActionPointValue;
use Games\Consts\ItemValue;
use Games\Consts\PVEValue;
use Games\Exceptions\PVEException;
use Games\Exceptions\UserException;
use Games\PVE\PVELevelHandler;
use Games\PVE\PVERacingUtility;
use Games\PVE\UserPVEHandler;
use Games\Users\UserHandler;
use Games\Users\UserUtility;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\BaseProcessor;

class PVERush extends BaseProcessor
{
    public function Process(): ResultData
    {
        $levelID = InputHelper::post('levelID');
        $rushCount = InputHelper::post('count');

        $userID = $_SESSION[Sessions::UserID];
        $userPVEHandler = new UserPVEHandler($userID);
        $medalAmount = $userPVEHandler->GetLevelMedalAmount($levelID);
        if($medalAmount !== PVEValue::LevelUnlockMedalAmount)
        throw new PVEException(PVEException::LevelCannotRush,['levelID' => $levelID]);

        $pveLevelInfo = (new PVELevelHandler($levelID))->GetInfo();
        $requiredPower = $pveLevelInfo->power * $rushCount;
        $userHandler = new UserHandler($userID);
        $userHandler->HandlePower(0);

        //體力不足
        if($userHandler->GetInfo()->power < $requiredPower)
        throw new UserException(UserException::UserPowerNotEnough);        
        $rewards = [];

        for($i = 0; $i < $rushCount; ++$i)
        {
            $rewards = array_merge(PVERacingUtility::GetLevelReward($levelID),$rewards);
        }

        UserUtility::AddItems($userID,$rewards,ItemValue::CausePVEClearLevel);
        $userHandler->HandlePower(-$requiredPower,ActionPointValue::CausePVERush,$levelID);

        $result = new ResultData(ErrorCode::Success);
        $result->rewards = $rewards;
        return $result;
    }
}