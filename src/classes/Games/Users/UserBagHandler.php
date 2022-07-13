<?php

namespace Games\Users;
use Games\Pools\UserBagItemPool;
use stdClass;
use Games\Users\Holders\UserItemHolder;
use Games\Users\UserItemHandler;

class UserBagHandler {

    private UserBagItemPool $pool;
    private int|string $id;
    
    public UserItemHolder|stdClass $info;
    

    public function __construct(int|string $id) {
        $this->pool = UserBagItemPool::Instance();
        $this->id = $id;
    }
    
    public function ResetInfo() : UserItemHolder|stdClass{
        $this->info = $this->pool->{$this->id};
        return $this->info;
    }

    public function GetInfo(int|string $id) : UserItemHolder|stdClass{
        $this->pool = UserBagItemPool::Instance();
        $this->id = $id;
        $this->ResetInfo();
        return $this->info;
    }
    
    public function DeleteCache(){
        $this->pool->Delete($this->id);
    }

    public function SaveData(array $bind) : void{
        $this->pool->Save($this->id, 'Data', $bind);
        $this->ResetInfo();
    }

    public function AddItem(int $id, int $itemID, int $amount){
        $UserItemHandler = new UserItemHandler($id);
        $UserItemHandler->AddItem($itemID,$amount);
    }


    public function UseItem(int $userItemID, int $amount) : array{
        $UserItemHandler = new UserItemHandler($userItemID);
        return $UserItemHandler->UseItem($amount);

    }

}