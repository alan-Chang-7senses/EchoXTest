<?php
namespace Games\Users;

use Games\Accessors\UserAccessor;
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

    public function ModifyPower(int $amount, int|null $updateTime = null)
    {
        $apInfo = APRecoverUtility::GetMaxAPAmountAndRecoverRate($this->id);
        $limit = $apInfo->maxAP;
        $currentPower = $this->info->power;
        $powerTemp = $currentPower + $amount;            
        //從滿體力 => 滿體力以下時，必須要有更新時間參數
        if($currentPower >= $limit && $powerTemp < $limit && $updateTime == null)
        {
            if($updateTime == null)throw new UserException(UserException::UserPowerError,['userID' => $this->info->id]);
        }
        $this->SaveData(['power' => $powerTemp >= 0 ? $powerTemp : 0]);
        if($updateTime != null)
        {
            (new UserAccessor)->ModifyUserValuesByID($this->id,['PowerUpdateTime' => $updateTime]);
        }        
    }
}
