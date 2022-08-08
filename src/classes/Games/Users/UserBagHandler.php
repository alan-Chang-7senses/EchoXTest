<?php

namespace Games\Users;
use stdClass;
use Games\Pools\ItemInfoPool;
use Games\Pools\UserBagItemPool;
use Games\Users\UserItemHandler;
use Games\Accessors\ItemAccessor;
use Games\Exceptions\UserException;
use Games\Users\Holders\UserItemHolder;

class UserBagHandler
{

    //背包是否可以有兩個相同物品ID
    private bool $multiItemID = false;

    private UserBagItemPool $bagPool;
    private ItemAccessor $itemAccessor;
    private ItemInfoPool $itemInfoPool;
    private int|string $userID;
    private stdClass $bagInfo;

    public function __construct(int|string $userID)
    {
        $this->bagPool = UserBagItemPool::Instance();
        $this->itemAccessor = new ItemAccessor();
        $this->itemInfoPool = ItemInfoPool::Instance();
        $this->userID = $userID;
        $this->bagInfo = $this->bagPool->{ $this->userID};
    }

    private function ResetInfo()
    {
        $this->bagPool->Delete($this->userID);
        $this->bagInfo = $this->bagPool->{ $this->userID};
    }

    public function GetItemInfos(): array
    {
        $result = [];
        foreach ($this->bagInfo->items as $userItemIDs) {

            foreach ($userItemIDs as $userItemID) {
                $userItemHandler = new UserItemHandler($userItemID);
                $result[] = $userItemHandler->GetInfo();
            }
        }
        return $result;
    }

    public function AddItem(int $itemID, int $amount)
    {
        $info = $this->itemInfoPool->{ $itemID};
        if ($info->StackLimit != 0) { //can stack
            if (isset($this->bagInfo->items->{$itemID})) {
                foreach ($this->bagInfo->items->{$itemID} as $userItemID) {
                    if ($amount <= 0) {
                        break;
                    }
                    $userItemHandler = new UserItemHandler($userItemID);
                    $amount = $userItemHandler->AddItem($amount);
                }

                if ($amount > 0) {
                    if ($this->multiItemID) {
                        $this->AddNewItem($itemID, $amount, $info->StackLimit);
                    }
                    else {
                    //todo add log to $amount, 物品未加完                        
                    }
                }

            }
            else {
                $this->AddNewItem($itemID, $amount, $info->StackLimit);
            }
        }
        else //no stack
        {
            for ($i = 0; $i < $amount; $i++) {
                $this->AddNewItem($itemID, 1, $info->StackLimit);
            }
        }

        $this->ResetInfo();
    }

    private function AddNewItem(int $itemID, int $amount, int $stackLimit)
    {

        if ($stackLimit != 0) { //can stack
            for ($i = 0; $i < 5; $i++) {

                if ($amount <= $stackLimit) {
                    $this->itemAccessor->AddItemByItemID($this->userID, $itemID, $amount);
                    break;
                }
                else {
                    $this->itemAccessor->AddItemByItemID($this->userID, $itemID, $stackLimit);
                    $amount -= $stackLimit;
                    if ($this->multiItemID == false) {
                        //todo add log to $amount, 物品未加完
                        break;
                    }
                }
            }
        }
        else //no stack
        {
            $this->itemAccessor->AddItemByItemID($this->userID, $itemID, 1);
        }
    }


    public function DecItem(int $userItemID, int $amount): bool
    {
        $userItemHandler = new UserItemHandler($userItemID);
        $userItemInfo = $userItemHandler->GetInfo();

        if ($userItemInfo->user != $this->userID) {
            throw new UserException(UserException::UserNotItemOwner, ['[userItemID]' => $userItemID]);
        }

        return $userItemHandler->DecItem($amount);
    }

    public static function GetUserItemInfo(int $userItemID): UserItemHolder|stdClass
    {
        $userItemHandler = new UserItemHandler($userItemID);
        return $userItemHandler->GetInfo();
    }


    public static function GetItemInfo(int $itemID): stdClass|false
    {
        $itemInfoPool = ItemInfoPool::Instance();
        $itemInfo = $itemInfoPool->{ $itemID};

        if ($itemInfo == false) {
            throw new UserException(UserException::ItemNotExists, ['[itemID]' => $itemID]);
        }


        $result = new stdClass();
        $result->name = $itemInfo->ItemName;
        $result->description = $itemInfo->Description;
        $result->stackLimit = $itemInfo->StackLimit;
        $result->itemType = $itemInfo->ItemType;
        $result->useType = $itemInfo->UseType;
        $result->source = $itemInfo->Source;
        return $result;
    }


}