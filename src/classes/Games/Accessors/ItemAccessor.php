<?php

namespace Games\Accessors;

use Consts\Globals;
/**
 * Description of ItemAccessor
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class ItemAccessor extends BaseAccessor{
    
    public function rowsUserItemByUserAssoc(int $id) : array{
        return $this->MainAccessor()->FromTable('UserItems')->WhereEqual('UserID', $id)->FetchStyleAssoc()->FetchAll();
    }
    
    public function rowItemByID(int $id) : mixed{
        return $this->StaticAccessor()->FromTable('ItemInfo')->WhereEqual('ItemID', $id)->Fetch();
    }
    
    public function rowItemDropByID(int $id) : mixed{
        return $this->StaticAccessor()->FromTable('ItemDrop')->WhereEqual('ItemDropID', $id)->Fetch();
    }
    
    public function rowUserItemByID(int $id) : mixed{
        return $this->MainAccessor()->FromTable('UserItems')->WhereEqual('UserItemID', $id)->Fetch();
    }
    
    public function ModifyUserItemByID(int $id, array $bind) : bool{
        return $this->MainAccessor()->FromTable('UserItems')->WhereEqual('UserItemID', $id)->Modify($bind);
    }
    
    public function AddLog(int $userItemID, int $userID, int $itemID, int $action, int $amount, int $remain) : mixed{
        return $this->LogAccessor()->Add([
            'UserItemID' => $userItemID,
            'UserID' => $userID,
            'ItemID' => $itemID,
            'Action' => $action,
            'Amount' => $amount,
            'Remain' => $remain,
            'LogTime' => $GLOBALS[Globals::TIME_BEGIN],
        ]);
    }
}
