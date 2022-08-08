<?php

namespace Games\Users;

use stdClass;
use Games\Pools\UserItemPool;
use Games\Exceptions\UserException;
use Games\Users\Holders\UserItemHolder;

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
            throw new UserException(UserException::UserNotItemOwner, ['[userItemID]' => $userItemID]);
        }

        $this->info = $info;
    }

    public function GetInfo(): UserItemHolder|stdClass
    {
        return $this->info;
    }

    public function AddItem(int $amount): int
    {
        if ($this->info->amount >= $this->info->stackLimit) {
            return $amount;
        }

        $newAmount = $this->info->amount + $amount;
        if ($newAmount <= $this->info->stackLimit) {
            //在範圍內,修改道具數量
            //var_dump('在範圍內,修改道具數量' . $this->info->amount . '加上的數量' . $amount);
            $this->pool->Save($this->userItemID, 'Amount', $newAmount);
            return 0;
        }
        else {
            //超出範圍,修改道具數量並增加道具
            //var_dump('超出範圍,修改道具數量並增加道具');
            $this->pool->Save($this->userItemID, 'Amount', $this->info->stackLimit);
            return $newAmount - $this->info->stackLimit;
        }
    }

    public function DecItem(int $amount): bool
    {
        if ($this->info->amount < $amount) {
            throw new UserException(UserException::ItemNotEnough, ['item' => $this->info->itemID]);
        }

        $remainAmount = $this->info->amount - $amount;
        $this->pool->Save($this->userItemID, 'Amount', $remainAmount);
        return true;
    }

}
