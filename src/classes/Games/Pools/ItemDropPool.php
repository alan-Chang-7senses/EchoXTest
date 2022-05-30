<?php

namespace Games\Pools;

use Accessors\PoolAccessor;
use Games\Accessors\ItemAccessor;
use stdClass;
/**
 * Description of ItemDropPool
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class ItemDropPool extends PoolAccessor{
    
    private static ItemDropPool $instance;
    
    public static function Instance() : ItemDropPool {
        if(empty(self::$instance)) self::$instance = new ItemDropPool ();
        return self::$instance;
    }

    protected string $keyPrefix = 'ItemDrop_';

    public function FromDB(int|string $id): stdClass|false {
        
        $itemAccessor = new ItemAccessor();
        return $itemAccessor->rowItemDropByID($id);
    }
}
