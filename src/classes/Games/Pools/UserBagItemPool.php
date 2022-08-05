<?php

namespace Games\Pools;

use Accessors\PoolAccessor;
use stdClass;
use Games\Accessors\ItemAccessor;

class UserBagItemPool extends PoolAccessor{
    
    private static UserBagItemPool $instance;
    
    public static function Instance() : UserBagItemPool {
        if(empty(self::$instance)) self::$instance = new UserBagItemPool();
        return self::$instance;
    }
    
    protected string $keyPrefix = 'UserBagItem_';

    public function FromDB(int|string $id): stdClass|false
    {
        $holder = new stdClass();
        $itemAccessor = new ItemAccessor();
        $rows = $itemAccessor->rowsUserItemByUserAssoc($id);
        $items = array();
        $itemIDs = array_column($rows, 'ItemID');
        $userItemID = array_column($rows, 'UserItemID');
        for($i = 0; $i< count($itemIDs); $i++){                 
            $items[$itemIDs[$i]][] = $userItemID[$i];
        }
        $holder->items = $items;
        return $holder;
    }
}
