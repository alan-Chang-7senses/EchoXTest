<?php

namespace Games\Pools;

use Accessors\PoolAccessor;
use stdClass;
use Games\Accessors\ItemAccessor;
/**
 * Description of ItemInfoPool
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class ItemInfoPool extends PoolAccessor {
    
    private static ItemInfoPool $instance;

    public static function Instance() : ItemInfoPool{
        if(empty(self::$instance)) self::$instance = new ItemInfoPool ();
        return self::$instance;
    }
    
    protected string $keyPrefix = 'Item_';

    public function FromDB(int|string $id): stdClass|false {
        
        $itemAccessor = new ItemAccessor();
        $row = $itemAccessor->rowItemByID($id);
        if($row === false) return false;
        
        $row->ItemDropIDs = $row->ItemDropIDs === null ? [] : explode(',', $row->ItemDropIDs);
        $row->Source = empty($row->Source) ? [] : explode(',', $row->Source);
        
        return $row;
    }

}
