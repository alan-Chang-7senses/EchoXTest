<?php

namespace Games\Users;

use Games\Accessors\ItemAccessor;
use Games\Consts\ItemValue;
use Games\Exceptions\ItemException;
use Games\Pools\UserItemPool;
use Games\Users\Holders\UserItemHolder;
use stdClass;

/**
 * Description of UserItemHandler
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class UserItemHandler
{

    private UserItemPool $pool;
    private int|string $userItemID;

    private UserItemHolder|stdClass $info;

    public function __construct(int|string $userItemID)
    {
        $this->pool = UserItemPool::Instance();
        $this->userItemID = $userItemID;
        $info = $this->pool->{ $this->userItemID};
        if ($info == false) {
            throw new ItemException(ItemException::UserNotItemOwner, ['[userItemID]' => $userItemID]);
        }

        $this->info = $info;
    }

    public function GetInfo(): UserItemHolder|stdClass
    {
        return $this->info;
    }

    public function AddItem(int $amount, int $cause): int
    {
        if ($this->info->amount >= $this->info->stackLimit) {
            return $amount;
        }

        $newAmount = $this->info->amount + $amount;
        $itemAccessor = new ItemAccessor();
        if ($newAmount <= $this->info->stackLimit) {
            //在範圍內,修改道具數量
            //var_dump('在範圍內,修改道具數量' . $this->info->amount . '加上的數量' . $amount);
            $this->pool->Save($this->userItemID, 'Amount', $newAmount);

            $itemAccessor->AddLog(
                $this->info->id, $this->info->user, $this->info->itemID, $cause, ItemValue::ActionObtain, $amount, $newAmount
            );

            return 0;
        }
        else {
            //超出範圍,修改道具數量並增加道具
            //var_dump('超出範圍,修改道具數量並增加道具');
            $addAmount = $this->info->stackLimit - $this->info->amount;

            $this->pool->Save($this->userItemID, 'Amount', $this->info->stackLimit);

            $itemAccessor->AddLog(
                $this->info->id, $this->info->user, $this->info->itemID, $cause, ItemValue::ActionObtain, $addAmount, $this->info->stackLimit
            );

            return $newAmount - $this->info->stackLimit;
        }
    }

    public static function AddNewItem(int $userID, int $itemID, int $amount, int $cause)
    {
        $itemAccessor = new ItemAccessor();
        $userItemID = $itemAccessor->AddItemByItemID($userID, $itemID, $amount);

        $itemAccessor->AddLog(
            $userItemID, $userID, $itemID, $cause, ItemValue::ActionObtain, $amount, $amount
        );
    }

    public function DecItem(int $amount, int $cause): bool
    {
        if ($this->info->amount < $amount) {
            throw new ItemException(ItemException::ItemNotEnough, ['item' => $this->info->itemID]);
        }

        $remainAmount = $this->info->amount - $amount;
        $this->pool->Save($this->userItemID, 'Amount', $remainAmount);

        $itemAccessor = new ItemAccessor();
        $itemAccessor->AddLog(
            $this->info->id, $this->info->user, $this->info->itemID, $cause, ItemValue::ActionUsed, $amount, $remainAmount
        );
        return true;
    }

}