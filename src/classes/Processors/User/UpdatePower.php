<?php

namespace Processors\User;

use Consts\ErrorCode;
use Consts\Globals;
use Consts\Sessions;
use Games\Accessors\UserAccessor;
use Games\Players\Exp\PlayerEXP;
use Games\Users\APRecoverUtility;
use Games\Users\UserHandler;
use Holders\ResultData;
use Processors\BaseProcessor;

class UpdatePower extends BaseProcessor
{
    public function Process(): ResultData 
    {
        $userID = $_SESSION[Sessions::UserID];
        $userAccessor = new UserAccessor();
        $userRow = $userAccessor->rowUserByID($userID);
        $lastUpdate = $userRow->PowerUpdateTime;
        $recoverInfo = APRecoverUtility::GetMaxAPAmountAndRecoverRate($userID);
        $limit = $recoverInfo->maxAP;
        $secondPerPower = $recoverInfo->rate;
        $currentTime = $GLOBALS[Globals::TIME_BEGIN];
        $lastPower = $userRow->Power;
        
        $results = new ResultData(ErrorCode::Success);
        //體力已滿或超過
        if($lastPower >= $limit)
        {
            // 還要回傳：距離回滿0時間
            $results->power = $lastPower;
            $results->timeRemain = 0;
            return $results;
        }

        $timeDiff = floor($currentTime - $lastUpdate);
        if($timeDiff >= ($limit - $lastPower) * $secondPerPower)
        {
            $bind = 
            [
                'power' => $limit
            ];
            $userHandler = new UserHandler($userID);
            $userHandler->SaveData($bind);
            return $results;
        }

        $addAmount = floor($timeDiff / $secondPerPower);
        $rtPowerTemp = PlayerEXP::Clamp($limit, 0, $lastPower + $addAmount);
        $nextPowerTimeRemain = ($limit - $rtPowerTemp) * $secondPerPower - $timeDiff % $secondPerPower;
        if ($addAmount > 0) 
        {
            $timeForUpdate = $lastUpdate + $secondPerPower * $addAmount;
            $userAccessor->ModifyUserValuesByID(
                $userID,
                [
                    'Power' => $rtPowerTemp,
                    'PowerUpdateTime' => $timeForUpdate,
                ]
            );
        }
        $results->power = $rtPowerTemp;
        $results->timeRemain = $nextPowerTimeRemain;
        return $results;
        
    }
}