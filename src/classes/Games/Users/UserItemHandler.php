<?php

namespace Games\Users;

use Games\Pools\UserItemPool;
use Games\Pools\UserBagItemPool;
use stdClass;
use Games\Users\Holders\UserItemHolder;
use Games\Exceptions\UserException;
use Games\Accessors\ItemAccessor;
use Games\Consts\ItemValue;
use Consts\Sessions;
use Consts\Globals;
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
    }

    public function ResetInfo() : UserItemHolder|stdClass{
        $this->info = $this->pool->{$this->id};
        return $this->info;
    }
    
    public function GetItemInfo(int|string $id){
        $this->pool = UserItemPool::Instance();
        $this->id = $id;
        $this->ResetInfo();
        return $this->info;
    }

    public function DeleteCache(){
        $this->pool->Delete($this->id);
    }

    public function AddItem(int $itemID, int $amount){
        $bagHandler = new UserBagHandler($_SESSION[Sessions::UserID]);
        $bagHandler->DeleteCache();
        $bagInfo = $bagHandler->GetInfo($_SESSION[Sessions::UserID]);
        $bisCheck = false;
        
        //判斷玩家是否擁有該道具
        foreach($bagInfo->items as $userItemID){
            $this->DeleteCache();
            $this->GetItemInfo($userItemID);
            if($userItemID == null)
            continue;
            if($this->info->itemID == $itemID)
            {
                //玩家擁有該道具
                var_dump('玩家擁有該道具');
                if($this->info->amount == $this->info->stackLimit)
                continue;
                
                $bisCheck = true;
                if($this->info->amount + $amount<= $this->info->stackLimit)
                {
                    //在範圍內,修改道具數量
                    var_dump('在範圍內,修改道具數量'.  $this->info->amount .'加上的數量'.$amount);
                    $bind = [
                        'Amount' => $this->info->amount + $amount,
                        'UpdateTime' => $GLOBALS[Globals::TIME_BEGIN],
                    ];
                    $itemAccessor = new ItemAccessor();
                    $itemAccessor->ModifyUserItemByID($this->info->id,$bind);
                    break;
                }else{
                    //超出範圍,修改道具數量並增加道具
                    var_dump('超出範圍,修改道具數量並增加道具');
                    $bind = [
                        'Amount' => $this->info->stackLimit,
                        'UpdateTime' => $GLOBALS[Globals::TIME_BEGIN],
                    ];
                    $itemAccessor = new ItemAccessor();
                    $itemAccessor->ModifyUserItemByID($this->info->id,$bind);

                    $remain = $this->info->amount + $amount - $this->info->stackLimit;
                    $itemAccessor->AddItemByItemID($_SESSION[Sessions::UserID],$itemID, $remain);
                    break;
                }
            }
        }
        
        //玩家沒有該道具
        if(!$bisCheck){
            var_dump('玩家沒有該道具');
            $itemAccessor = new ItemAccessor();
            $itemAccessor->AddItemByItemID($_SESSION[Sessions::UserID],$itemID, $amount);
        }
        
        UserBagItemPool::Instance()->Delete($_SESSION[Sessions::UserID]);
    }

    public function UseItem(int $amount) : array|false{
        $remain  = $this->info->amount - $amount;
        if($remain < 0) throw new UserException (UserException::ItemNotEnough, ['item' => $this->info->itemID]);
        
        // Use
        $useArray = [
            'Amount' => $remain,
        ];
        //$this->pool->Save($this->info->id, 'Amount', $remain);
        //(new ItemAccessor())->AddLog($this->info->id, $this->info->user, $this->info->itemID, ItemValue::ActionUsed, $amount, $remain);
       
        // Drop
        $returnItems = [];
        $itemsArray = [];
        if($this->info->dropType == 1){
            for($i = 0;$i<$amount;$i++)
            {
                $rand = rand(0, count($this->info->itemDrops)-1);
                var_dump('隨機? ' . $rand);
                var_dump('總數? ' . count($this->info->itemDrops));
                $rands = [];
                $rands[$i] = $rand;
                for($j= 0; $j< count($this->info->itemDrops); $j++){
                    if($j == $rand){
                        //var_dump($this->info->itemDrops[$j]->ItemID);
                        $itemsArray[] = [
                            'id' => $this->info->id,
                            'itemID' => $this->info->itemDrops[$j]->ItemID,
                            'amoount' => $this->info->itemDrops[$j]->Amount*$this->info->dropCount,
                        ];
                    }
                }
            }
            
        $itemAccessor = new ItemAccessor();
        $itemAccessor->Transaction($this->info->id,$useArray,$itemsArray);
        }else if($this->info->dropType == 2){
            for($i = 0;$i<$amount;$i++)
            {
                var_dump('全給'.$this->info->dropCount.'總數?'.count($this->info->itemDrops));
                for($j = 0;$j<count($this->info->itemDrops);$j++)
                {
                    //var_dump('ID = '.$this->info->itemDrops[$j]->ItemID. ' Amount = '.$this->info->itemDrops[$j]->Amount);
                    $itemsArray[] =[
                        'id' => $this->info->id,
                        'itemID' => $this->info->itemDrops[$j]->ItemID,
                        'amoount' => $this->info->itemDrops[$j]->Amount*$this->info->dropCount,
                    ];
                    //$this->UseItemDrop($this->info->id,$this->info->itemDrops[$j]->ItemID,$this->info->itemDrops[$j]->Amount*$this->info->dropCount);
                }
            }
            
        $itemAccessor = new ItemAccessor();
        $itemAccessor->Transaction($this->info->id,$useArray,$itemsArray);
        }else if($this->info->dropType == 3){
            for($i = 0;$i<$amount;$i++)
            {
                $total = 0;
                foreach($this->info->itemDrops as $itemDrops){
                    $total+=$itemDrops->Proportion;
                }
                var_dump('總權重 = '.$total);
                $rand = rand(1, $total);
                $added = 0;
                var_dump('隨機數 = '.$rand);
                for($j = 0; $j < count($this->info->itemDrops);$j++){
                    $added = $added + $this->info->itemDrops[$j]->Proportion;
                    var_dump('加總數 = '.$added);
                    if($rand<=$added)
                    {
                        var_dump($this->info->itemDrops[$j]->ItemID);
                        $itemsArray[] =[
                            'id' => $this->info->id,
                            'itemID' => $this->info->itemDrops[$j]->ItemID,
                            'amoount' => $this->info->itemDrops[$j]->Amount*$this->info->dropCount,
                        ];
                        //$this->UseItemDrop($this->info->id,$this->info->itemDrops[$j]->ItemID,$this->info->itemDrops[$j]->Amount*$this->info->dropCount);
                        break;
                    }
                } 
            }
            
        $itemAccessor = new ItemAccessor();
        $itemAccessor->Transaction($this->info->id,$useArray,$itemsArray);
        }else if($this->info->dropType == 4){
            for($i = 0;$i<$amount;$i++)
            {
                for($j = 0;$j<count($this->info->itemDrops);$j++)
                {
                    //var_dump($this->info->itemDrops[$i]);
                        $returnItems[$i] = [
                            'itemID' => $this->info->itemDrops[$j]->ItemID,
                            'dropCount' => $this->info->itemDrops[$j]->Amount * $amount,
                        ];
                }
            }
        } 
        
        return $returnItems;
    }


    public function UseItemDrop(int $itemID, int $amount){
        
        $bagHandler = new UserBagHandler($_SESSION[Sessions::UserID]);
        $bagHandler->DeleteCache();
        $bagInfo = $bagHandler->GetInfo($_SESSION[Sessions::UserID]);
        $bisCheck = false;
        //判斷玩家是否擁有該道具
        foreach($bagInfo->items as $userItemID){
            $this->DeleteCache();
            $this->GetItemInfo($userItemID);
            if($userItemID == null)
            continue;
            if($this->info->itemID == $itemID)
            {
                if($this->info->amount == $this->info->stackLimit)
                continue;
                
                //玩家擁有該道具
                var_dump('玩家擁有該道具');
                $bisCheck = true;
                if($this->info->amount + $amount<= $this->info->stackLimit)
                {
                    //在範圍內,修改道具數量
                    var_dump('在範圍內,修改道具數量'.  $this->info->amount .'加上的數量'.$amount);
                    $bind = [
                        'Amount' => $this->info->amount + $amount,
                        'UpdateTime' => $GLOBALS[Globals::TIME_BEGIN],
                    ];
                    $itemAccessor = new ItemAccessor();
                    $itemAccessor->ModifyUserItemByID($this->info->id,$bind);
                    break;
                }else{
                    //超出範圍,修改道具數量並增加道具
                    var_dump('超出範圍,修改道具數量並增加道具');
                    $bind = [
                        'Amount' => $this->info->stackLimit,
                        'UpdateTime' => $GLOBALS[Globals::TIME_BEGIN],
                    ];
                    $itemAccessor = new ItemAccessor();
                    $itemAccessor->ModifyUserItemByID($this->info->id,$bind);

                    $remain = $this->info->amount + $amount - $this->info->stackLimit;
                    $itemAccessor->AddItemByItemID($_SESSION[Sessions::UserID],$itemID, $remain);
                    break;
                }
            }
        }
        
        //玩家沒有該道具
        if(!$bisCheck){
            var_dump('玩家沒有該道具');
            $itemAccessor = new ItemAccessor();
            $itemAccessor->AddItemByItemID($_SESSION[Sessions::UserID],$itemID, $amount);
        }

        UserBagItemPool::Instance()->Delete($_SESSION[Sessions::UserID]);
    }
}
