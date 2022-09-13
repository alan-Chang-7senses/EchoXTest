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
     * @param int $addAmount 值為0時為單純更新體力，小於零時扣除體力。
     * @param int $cause 有消耗或增加體力時辨識使用原由。Log需求。
     * @param int $pveLevel 因PVE消耗體力時，消耗的關卡代號。
     * @return bool 體力不足時回傳false
     */
    public function HandlePower(int $addAmount, int $cause = ActionPointValue::CauseNone, ?int $pveLevel = null) : bool
    {
        $powerOld = $this->info->power;
        $userAccessor = new UserAccessor();
        $row = $userAccessor->rowPowerByID($this->info->id);
        $apInfo = APRecoverUtility::GetMaxAPAmountAndRecoverRate($this->id);
        $lastUpdateTime = $row === false ? 0 : $row->PowerUpdateTime;
        $natureUpdatePowerInfo = APRecoverUtility::GetCurrentAPInfo($powerOld,$lastUpdateTime,$this->info->id);
        $updatedPower = $natureUpdatePowerInfo->power;
        $updateTime = isset($natureUpdatePowerInfo->powerUpdateTime) ? $natureUpdatePowerInfo->powerUpdateTime : null;

        $limit = $apInfo->maxAP;
        $powerAdded = $updatedPower + $addAmount;
        //體力不足
        if($powerAdded < 0)return false;
        //從滿體力 => 滿體力以下時，計時重制
        if($updatedPower >= $limit && $powerAdded < $limit)
        {
            $updateTime = $GLOBALS[Globals::TIME_BEGIN];
        }

        if($powerOld != $powerAdded)
        $this->SaveData(['power' => $powerAdded]);

        if($updateTime != null)
        {
            $userAccessor->UpdatePowerTimeByID($this->id,['PowerUpdateTime' => floor($updateTime)]);
        }

        if($addAmount != 0)
        (new GameLogAccessor())->AddUsePowerLog($cause,$updatedPower,$powerAdded,$pveLevel);
        return true;
    }
}
