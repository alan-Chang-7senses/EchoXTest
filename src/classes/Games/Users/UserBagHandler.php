<?php

namespace Games\Users;
use stdClass;
use Exception;
use Consts\ErrorCode;
use Games\Pools\ItemInfoPool;
use Games\Pools\UserBagItemPool;
use Games\Users\UserItemHandler;
use Games\Accessors\ItemAccessor;
use Games\Exceptions\ItemException;
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

    public function GetItemAmount(int $itemID): int
    {
        $amount = 0;
        if (isset($this->bagInfo->items->{ $itemID})) {
            foreach ($this->bagInfo->items->{ $itemID} as $userItemID) {
                $userItemHandler = new UserItemHandler($userItemID);
                $amount += $userItemHandler->GetInfo()->amount;
            }
        }
        return $amount;
    }

    public function CheckAddStacklimit(array $items): bool
    {
        if ($this->multiItemID) {
            return true;
        }

        foreach ($items as $item) {
            if (($item->ItemID <= 0) || ($item->Amount <= 0)) {
                continue;
            }

            $info = $this->itemInfoPool->{ $item->ItemID};
            if ($info->StackLimit == 0) {
                continue;
            }

            if (isset($this->bagInfo->items->{ $item->ItemID})) {

                $userItemHandler = new UserItemHandler($this->bagInfo->items->{ $item->ItemID}[0]);
                if ($item->Amount + $userItemHandler->GetInfo()->amount > $info->StackLimit) {
                    return false;
                }
            }
        }
        return true;
    }


    public function AddItem(int $itemID, int $amount)
    {
        if (($itemID == 0) || ($amount <= 0)) {
            throw new Exception('The itemID \'' . $itemID . '\' or amount\'' . $amount . '\'  can not <= 0', ErrorCode::ParamError);
        }

        if ($itemID < 0) {
            $userHandler = new UserHandler($this->userid);
            $info = $userHandler->GetInfo();
            switch ($itemID) {
                case -1: //-1 電力
                    $info->power += $amount;
                    $userHandler->SaveData(['Power' => $info->power]);
                    break;
                case -2: //-2 金幣
                    $info->coin += $amount;
                    $userHandler->SaveData(['Coin' => $info->coin]);
                    break;
                case -3: //-3 寶石
                    $info->diamond += $amount;
                    $userHandler->SaveData(['Diamond' => $info->diamond]);
                    break;
                case -4: //-4 PT
                    $info->petaToken += $amount;
                    $userHandler->SaveData(['PetaToken' => $info->petaToken]);
                    break;
            }
        }
        else {
            $info = $this->itemInfoPool->{ $itemID};
            if ($info->StackLimit != 0) { //can stack
                if (isset($this->bagInfo->items->{ $itemID})) {
                    foreach ($this->bagInfo->items->{ $itemID} as $userItemID) {
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
        if (($userItemID <= 0) || ($amount <= 0)) {
            throw new Exception('The userItemID \'' . $userItemID . '\' or amount\'' . $amount . '\'  can not <= 0', ErrorCode::ParamError);
        }

        $userItemHandler = new UserItemHandler($userItemID);
        $userItemInfo = $userItemHandler->GetInfo();

        if ($userItemInfo->user != $this->userID) {
            throw new ItemException(ItemException::UserNotItemOwner, ['[userItemID]' => $userItemID]);
        }

        return $userItemHandler->DecItem($amount);
    }

    public function GetUserItemInfo(int $userItemID): UserItemHolder|stdClass
    {
        $userItemHandler = new UserItemHandler($userItemID);
        return $userItemHandler->GetInfo();
    }

}