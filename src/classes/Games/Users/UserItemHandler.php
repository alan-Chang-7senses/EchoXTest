<?php

namespace Games\Users;

use Games\Pools\UserItemPool;
use stdClass;
use Games\Users\Holders\UserItemHolder;
use Games\Exceptions\UserException;
use Games\Accessors\ItemAccessor;
use Games\Consts\ItemValue;
/**
 * Description of UserItemHandler
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class UserItemHandler {
    
    private UserItemPool $pool;
    private int|string $id;
    
    public UserItemHolder|stdClass $info;
    
    public function __construct(int|string $id) {
        $this->pool = UserItemPool::Instance();
        $this->id = $id;
        $this->ResetInfo();
    }
    
    public function ResetInfo() : UserItemHolder|stdClass{
        $this->info = $this->pool->{$this->id};
        return $this->info;
    }
    
    public function Use(int $amount) : array|false{
        
        $remain  = $this->info->amount - $amount;
        if($remain <= 0) throw new UserException (UserException::ItemNotEnough, ['item' => $this->info->itemID]);
        
        // Use
        
        $this->pool->Save($this->id, 'Amount', $remain);
        (new ItemAccessor())->AddLog($this->id, info, $this->info->itemID, ItemValue::ActionUsed, $amount, $remain);
        
        // Drop
        return [];
    }
}
