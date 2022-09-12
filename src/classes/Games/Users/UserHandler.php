<?php
namespace Games\Users;

use Consts\Globals;
use Games\Accessors\GameLogAccessor;
use Games\Accessors\UserAccessor;
use Games\Consts\ActionPointValue;
use Games\Consts\PowerValue;
use Games\Exceptions\UserException;
use Games\Pools\UserPool;
use Games\Users\Holders\UserInfoHolder;
use stdClass;
/**
 * Description of UserHandler
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class UserHandler {
    
    private UserPool $pool;
    private int|string $id;
    private UserInfoHolder|stdClass $info;
    
    public function __construct(int|string $id) {
        $this->pool = UserPool::Instance();
        $this->id = $id;
        $info = $this->pool->$id;
        if($info === false) throw new UserException(UserException::UserNotExist, ['[user]' => $id]);
        $this->info = $info;
    }
    
    private function ResetInfo() : void{
        $this->info = $this->pool->{$this->id};
    }

    public function GetInfo() : UserInfoHolder|stdClass{
        return $this->info;
    }
    
    public function SaveData(array $bind) : void{
        $this->pool->Save($this->id, 'Data', $bind);
        $this->ResetInfo();
    }

    /**
     * 增加或減少體力。會自動更新正確的體力。
     * @param int $addAmount 值為0時為單純更新體力
     */
    public function HandlePower(int $addAmount, int $cause = ActionPointValue::CauseNone) : bool
    {
        $currentPower = $this->info->power;
        $powerBefore = $currentPower;
        $userAccessor = new UserAccessor();
        $row = $userAccessor->rowPowerByID($this->info->id);
        $apInfo = APRecoverUtility::GetMaxAPAmountAndRecoverRate($this->id);
        $lastUpdateTime = $row === false ? 0 : $row->PowerUpdateTime;
        $natureUpdatePowerInfo = APRecoverUtility::GetCurrentAPInfo($currentPower,$lastUpdateTime,$this->info->id);
        $currentPower = $natureUpdatePowerInfo->power;
        $updateTime = isset($natureUpdatePowerInfo->powerUpdateTime) ? $natureUpdatePowerInfo->powerUpdateTime : null;

        $limit = $apInfo->maxAP;
        $powerTemp = $currentPower + $addAmount;
        if($powerTemp < 0)return false;
        //從滿體力 => 滿體力以下時，計時重制
        if($currentPower >= $limit && $powerTemp < $limit)
        {
            $updateTime = $GLOBALS[Globals::TIME_BEGIN];
        }

        if($powerBefore != $powerTemp)
        $this->SaveData(['power' => $powerTemp]);

        if($updateTime != null)
        {
            $userAccessor->UpdatePowerTimeByID($this->id,['PowerUpdateTime' => floor($updateTime)]);
        }
        //需要寫LOG
        if($cause != ActionPointValue::CauseNone)
        (new GameLogAccessor())->AddUsePowerLog($cause,$powerBefore,$powerTemp);

        return true;
    }
}
