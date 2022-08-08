<?php

namespace Games\Pools;

use stdClass;
use Consts\Globals;
use Accessors\PoolAccessor;
use Games\Pools\ItemDropPool;
use Games\Pools\ItemInfoPool;
use Games\Accessors\ItemAccessor;
use Games\Exceptions\UserException;
use Games\Users\Holders\UserItemHolder;
/**
 * Description of UserItemsPool
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class UserItemPool extends PoolAccessor{
    
    private static UserItemPool $instance;
    
    public static function Instance() : UserItemPool{
        if(empty(self::$instance)) self::$instance = new UserItemPool ();
        return self::$instance;
    }
    
    protected string $keyPrefix = 'UserItem_';

    public function FromDB(int|string $id): stdClass|false {
        
        $itemAccessor = new ItemAccessor();
        $row = $itemAccessor->rowUserItemByID($id);
        if($row === false) return false;
        
        $holder = new UserItemHolder();
        $holder->id = $id;
        $holder->user = $row->UserID;
        $holder->itemID = $row->ItemID;
        $holder->userItemsID = $row->UserItemID;        
        $holder->amount = $row->Amount;
        $holder->createTime = $row->CreateTime;
        $holder->updateTime = $row->UpdateTime;
        
        $itemInfo = ItemInfoPool::Instance()->{$holder->itemID};

        if ($itemInfo == false)
        {
            throw new UserException(UserException::ItemNotExists,['itemID' => $holder->itemID]);
        }


        $holder->itemName = $itemInfo->ItemName;
        $holder->description = $itemInfo->Description;
        $holder->itemType = $itemInfo->ItemType;
        $holder->icon = $itemInfo->Icon;
        $holder->stackLimit = $itemInfo->StackLimit;
        $holder->useType = $itemInfo->UseType;
        $holder->effectType = $itemInfo->EffectType;
        $holder->effectValue = $itemInfo->EffectValue;
        $holder->rewardID = $itemInfo->RewardID;        
        $holder->source = $itemInfo->Source;
        
        return $holder;
    }
    
    protected function SaveAmount(stdClass $data, int $value) : stdClass{
        
        $bind = [
            'Amount' => $value,
            'UpdateTime' => $GLOBALS[Globals::TIME_BEGIN],
        ];
        
        $itemAccessor = new ItemAccessor();
        
        $result = $itemAccessor->ModifyUserItemByID($data->id, $bind);
        
        if($result === true){
            $data->amount = $value;
            $data->updateTime = (int)$GLOBALS[Globals::TIME_BEGIN];
        }
        
        return $data;
    }
}
