<?php
namespace Games\Users;

use Consts\Globals;
use Games\Consts\ActionPointValue;
use Games\Players\Exp\PlayerEXP;
use stdClass;

class APRecoverUtility
{
    /**使用秒數，取得小時、分鐘、秒數格式 */
    public static function GetTimeInfoBySecond(int $second) : stdClass
    {
        $hours = floor($second / 3600);
        $minutes = floor(($second - $hours * 3600)/60);
        $second = $second - $hours * 3600 - $minutes * 60;
        // return str_pad($hours,2,'0').':'.str_pad($minutes,2,'0').':'.str_pad($second,2,'0');
        $rt = new stdClass();
        $rt->hours = $hours;
        $rt->minutes = $minutes;
        $rt->seconds = $second;
        return $rt;
    }

    /**取得最大體力與回復頻率 */
    public static function GetMaxAPAmountAndRecoverRate(int $userID) : stdClass
    {
        // $timeDiff = self::GetUpdateTimeDifference($userID);
        $nftPlayerAmount = UserUtility::GetUserNFTPlayerAmount($userID);
        $amountKey = PlayerEXP::Clamp(ActionPointValue::APRankMoreThanTen,
                                    ActionPointValue::APRankLessThanOne,$nftPlayerAmount);
        $rt = new stdClass();
        $rt->maxAP = ActionPointValue::APRecoverInfo[$amountKey][ActionPointValue::APLimit];
        $rt->rate = ActionPointValue::APRecoverInfo[$amountKey][ActionPointValue::APIncreaseRate];
        return $rt;
    }

    /**取得到最到體力還需多少秒。必須先更新過體力後再使用較為準確 */
    public static function GetFullAPTime(int $userID, int $lastUpdateTime)
    {
        $recoverInfo = self::GetMaxAPAmountAndRecoverRate($userID);
        $limit = $recoverInfo->maxAP;
        $recoverRate = $recoverInfo->rate;
        $timeDiff = floor($GLOBALS[Globals::TIME_BEGIN] - $lastUpdateTime);
        $apDiff = $limit - (new UserHandler($userID))->GetInfo()->power;
        $rt = $apDiff * $recoverRate - $timeDiff % $recoverRate;
        return $rt > 0 ? $rt : 0;
    }

    /**
     * 取得目前電力
     * @param int $lastPower 更新前電力
     * @param int $powerLastUpdate 更新前使用者欄位PowerUpdateTime
     * @param int $userID 使用者ID
     * @return stdClass 欄位：power=>更新後電力、powerUpdateTime =>給DB使用者欄位更新的時間戳，不需更新則無此欄位
     */
    public static function GetCurrentAPInfo(int $lastPower, int $powerLastUpdate, int $userID) : stdClass
    {
        $results = new stdClass();
        $recoverInfo = self::GetMaxAPAmountAndRecoverRate($userID);
        $limit = $recoverInfo->maxAP;
        $recoverRate = $recoverInfo->rate;        
        $currentTime = $GLOBALS[Globals::TIME_BEGIN];        
        if($lastPower >= $limit)
        {
            $results->power = $lastPower;
            // $results->timeRemain = 0;
            return $results;
        }
        
        $timeDiff = floor($currentTime - $powerLastUpdate);
        if($timeDiff >= ($limit - $lastPower) * $recoverRate)
        {
            $results->power = $limit;
            // $results->timeRemain = 0;
            return $results;
        }

        $addAmount = floor($timeDiff / $recoverRate);
        $rtPower = $lastPower + $addAmount;
        // $nextPowerTimeRemain = ($limit - $rtPower) * $recoverRate - $timeDiff % $recoverRate;
        if ($addAmount > 0) 
        {
            $timeForUpdate = $powerLastUpdate + $recoverRate * $addAmount;
            $results->powerUpdateTime = $timeForUpdate;
        }
        $results->power = $rtPower;
        // $results->timeRemain = $nextPowerTimeRemain;
        return $results;
    }
    
}