<?php

namespace Games\Pools;

use Accessors\PoolAccessor;
use Games\Accessors\PlayerAccessor;
use Games\Accessors\UserAccessor;
use Games\Users\Holders\UserInfoHolder;
use stdClass;
use Games\Accessors\ItemAccessor;

class UserBagItemPool extends PoolAccessor{
    
    private static UserBagItemPool $instance;
    
    public static function Instance() : UserBagItemPool {
        if(empty(self::$instance)) self::$instance = new UserBagItemPool();
        return self::$instance;
    }
    
    protected string $keyPrefix = 'UserBagItem_';

    public function FromDB(int|string $id): stdClass|false {
        
        $userAccessor = new UserAccessor();
        $row = $userAccessor->rowUserByID($id);
        if(empty($row)) return false;
        
        $holder = new UserInfoHolder();

        $itemAccessor = new ItemAccessor();
        $rows = $itemAccessor->rowsUserItemByUserAssoc($id);
        $holder->items = array_column($rows, 'UserItemID');
        
        return $holder;
    }
}
