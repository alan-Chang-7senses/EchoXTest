<?php

namespace Games\Accessors;

use Consts\Globals;
use Games\Users\UserItemHandler;

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
        return $this->LogAccessor()->FromTable('UserItemsLog')->Add([
            'UserItemID' => $userItemID,
            'UserID' => $userID,
            'ItemID' => $itemID,
            'Action' => $action,
            'Amount' => $amount,
            'Remain' => $remain,
            'LogTime' => $GLOBALS[Globals::TIME_BEGIN],
        ]);
    }


    public function UserItemByItemID(int $userID, int $itemID) : mixed{
        return $this->MainAccessor()->FromTable('UserItems')->WhereEqual('UserItemID', $userID)->WhereEqual('UserItemID', $itemID)->Fetch();
    }
    
    public function AddItemByItemID(int $userID, int $itemID, int $amount) : bool{
        return $this->MainAccessor()->FromTable('UserItems')->Add([
            'UserItemID' => null,
            'UserID' => $userID,
            'ItemID' => $itemID,
            'Amount' => $amount,
            'CreateTime' => $GLOBALS[Globals::TIME_BEGIN],
            'UpdateTime' => $GLOBALS[Globals::TIME_BEGIN],
        ]);
    }

    public function Transaction(int $id,array $usedItem, array $dropItem){
        $this->id = $id;
        $this->usedItem = $usedItem;
        $this->dropItem = $dropItem;

        $this->MainAccessor()->Trasaction(function(){
            $this->ModifyUserItemByID($this->id,$this->usedItem);
            $userItemHandler = new UserItemHandler($this->id);
            for($i=0; $i<count($this->dropItem); $i++){
                //var_dump($this->dropItem[$i]);
                $userItemHandler->UseItemDrop($this->dropItem[$i]['itemID'],$this->dropItem[$i]['amoount']);
            }
        });
    }
}
