<?php

namespace Games\Users;

use Consts\ErrorCode;
use Exception;
use Games\Accessors\ItemAccessor;
use Games\Consts\ItemValue;
use Games\Exceptions\ItemException;
use Games\Pools\ItemInfoPool;
use Games\Pools\UserBagItemPool;
use Games\Users\Holders\UserItemHolder;
use Games\Users\UserItemHandler;
use stdClass;

class UserBagHandler {

    //背包是否可以有兩個相同物品ID
    private bool $multiItemID = false;
    private UserBagItemPool $bagPool;
    private ItemInfoPool $itemInfoPool;
    private int|string $userID;
    private stdClass $bagInfo;

    public function __construct(int|string $userID) {
        $this->bagPool = UserBagItemPool::Instance();
        $this->itemInfoPool = ItemInfoPool::Instance();
        $this->userID = $userID;
        $this->bagInfo = $this->bagPool->{ $this->userID};
    }

    private function ResetInfo() {
        $this->bagPool->Delete($this->userID);
        $this->bagInfo = $this->bagPool->{ $this->userID};
    }

    public function GetItemInfos(): array {
        $result = [];
        foreach ($this->bagInfo->items as $userItemIDs) {

            foreach ($userItemIDs as $userItemID) {
                $userItemHandler = new UserItemHandler($userItemID);
                $result[] = $userItemHandler->GetInfo();
            }
        }
        return $result;
    }

    public function GetItemAmount(int $itemID): int {

        if ($itemID < 0) {
            return $this->GetCurrencyAmount($itemID);
        } else {
            $amount = 0;
            if (isset($this->bagInfo->items->{ $itemID})) {
                foreach ($this->bagInfo->items->{ $itemID} as $userItemID) {
                    $userItemHandler = new UserItemHandler($userItemID);
                    $amount += $userItemHandler->GetInfo()->amount;
                }
            }
            return $amount;
        }
    }

    private function GetCurrencyAmount(int $itemID): int {
        $userHandler = new UserHandler($this->userID);
        $info = $userHandler->GetInfo();
        return match ($itemID) {
            ItemValue::CurrencyPower => $info->power,
            ItemValue::CurrencyCoin => $info->coin,
            ItemValue::CurrencyDiamond => $info->diamond,
            ItemValue::CurrencyPetaToken => $info->petaToken,
            default => 0
        };
    }

    private function CheckCurrencyID(int $itemID): bool {
        return match ($itemID) {
            ItemValue::CurrencyPower,
            ItemValue::CurrencyCoin,
            ItemValue::CurrencyDiamond,
            ItemValue::CurrencyPetaToken => true,
            default => false
        };
    }

    public function CheckAddStacklimit(array|stdclass $items): bool {
        if ($this->multiItemID) {
            return true;
        }

        if (is_array($items)) {
            foreach ($items as $item) {
                if ($this->CheckItemStack($item->ItemID, $item->Amount) == false) {
                    return false;
                }
            }
            return true;
        } else {
            return $this->CheckItemStack($items->ItemID, $items->Amount);
        }
    }

    private function CheckItemStack(int $itemID, int $amount): bool {
        if ($amount < 0) {
            throw new ItemException(ItemException::AddItemError, ['[itemID]' => $itemID]);
        }

        if ($itemID < 0) {
            // 貨幣資訊：不需檢查堆疊，只需檢查幣值種類
            return $this->CheckCurrencyID($itemID);
        } else {
            //一般物品
            $info = $this->itemInfoPool->{ $itemID};
            if ($info === false) {
                throw new ItemException(ItemException::ItemNotExists, ['itemID' => $itemID]);
            }

            if ($info->StackLimit === ItemValue::ItemCannotStack) {
                return true;
            }

            //if multiItemID == true no need to check, so multiItemID == false only have "one" data
            if (isset($this->bagInfo->items->{ $itemID})) {
                $userItemHandler = new UserItemHandler($this->bagInfo->items->{ $itemID}[0]);
                if (($amount + $userItemHandler->GetInfo()->amount) > $info->StackLimit) {
                    return false;
                }
            }
            else if ($amount > $info->StackLimit) {
                // 假設背包內原本就無此道具 > 若要檢查新增的道具數量 直接超過道具最大上限數量 > 直接回傳失敗
                return false;
            }
            return true;
        }
    }

    public function AddItems(array|stdClass $items, int $cause = ItemValue::CauseDefault): bool {
        if ($this->CheckAddStacklimit($items) == false) {
            return false;
        }

        if (is_array($items)) {
            foreach ($items as $item) {
                $this->AddItem($item->ItemID, $item->Amount, $cause);
            }
        } else {
            $this->AddItem($items->ItemID, $items->Amount, $cause);
        }

        return true;
    }

    //使用AddItems已確定加入物品沒問題
    private function AddItem(int $itemID, int $amount, int $cause) {
        if (($itemID == 0) || ($amount < 0)) {
            throw new Exception('The itemID \'' . $itemID . '\' or amount\'' . $amount . '\'  can not <= 0', ErrorCode::ParamError);
        }

        if ($itemID < 0) {
            $userHandler = new UserHandler($this->userID);
            $info = $userHandler->GetInfo();
            $lastAmount = 0;
            switch ($itemID) {
                case ItemValue::CurrencyPower: //-1 電力
                    $lastAmount = $info->power + $amount;
                    $userHandler->SaveData(['power' => $lastAmount]);
                    break;
                case ItemValue::CurrencyCoin: //-2 金幣
                    $lastAmount = $info->coin + $amount;
                    $userHandler->SaveData(['coin' => $lastAmount]);
                    break;
                case ItemValue::CurrencyDiamond: //-3 寶石
                    $lastAmount = $info->diamond + $amount;
                    $userHandler->SaveData(['diamond' => $lastAmount]);
                    break;
                case ItemValue::CurrencyPetaToken: //-4 PT
                    $lastAmount = $info->petaToken + $amount;
                    $userHandler->SaveData(['petaToken' => $lastAmount]);
                    break;
            }

            $itemAccessor = new ItemAccessor();
            $itemAccessor->AddLog(0, $this->userID, $itemID, $cause, ItemValue::ActionObtain, $amount, $lastAmount);
        } else {
            $info = $this->itemInfoPool->{ $itemID};
            if ($info->StackLimit !== ItemValue::ItemCannotStack) { //can stack
                if (isset($this->bagInfo->items->{ $itemID})) {
                    foreach ($this->bagInfo->items->{ $itemID} as $userItemID) {
                        if ($amount <= 0) {
                            break;
                        }
                        $userItemHandler = new UserItemHandler($userItemID);
                        $amount = $userItemHandler->AddItem($amount, $cause);
                    }

                    if ($amount > 0) {
                        if ($this->multiItemID) {
                            $this->AddNewItem($itemID, $amount, $info->StackLimit, $cause);
                        } else {
                            //todo add log to $amount, 物品未加完
                        }
                    }
                } else {
                    $this->AddNewItem($itemID, $amount, $info->StackLimit, $cause);
                }
            } else { //no stack
                $this->AddNewItem($itemID, $amount, $info->StackLimit, $cause);
            }
            $this->ResetInfo();
        }
    }

    //add to 'UserItems' table
    private function AddNewItem(int $itemID, int $amount, int $stackLimit, int $cause) {

        if ($stackLimit !== ItemValue::ItemCannotStack) { //can stack
            if ($amount <= $stackLimit) {
                UserItemHandler::AddNewItem($this->userID, $itemID, $amount, $cause);
            } else {
                if ($this->multiItemID) {
                    for ($i = 0; $i < 5; $i++) { //test five times to add itemms
                        UserItemHandler::AddNewItem($this->userID, $itemID, $stackLimit, $cause);
                        $amount -= $stackLimit;
                        if ($amount <= $stackLimit) {
                            UserItemHandler::AddNewItem($this->userID, $itemID, $amount, $cause);
                            break;
                        }
                    }
                } else {
                    //why can add items over stack limit one time, design logic error
                    throw new Exception('Add new item too much over the stack limit, ItemID: ' . $itemID . ' amount:' . $amount, ErrorCode::ParamError);
                }
            }
        } else { //can't stack
            for ($i = 0; $i < $amount; $i++) {
                UserItemHandler::AddNewItem($this->userID, $itemID, 1, $cause);
            }
        }
    }

    public function DecItems(array|stdclass $items, int $cause = ItemValue::CauseDefault): bool {
        if (is_array($items)) {
            $decItems = [];
            foreach ($items as $item) {
                $tempItem = $this->DecItemByItemID($item->ItemID, $item->Amount, $cause, true);
                if ($tempItem == false) {
                    return false;
                }
                $decItems = array_merge($decItems, $tempItem);
            }

            foreach ($decItems as $decItem) {
                $this->DecItem($decItem->userItemID, $decItem->amount, $cause);
            }
            return true;
        } else {
            return $this->DecItemByItemID($items->ItemID, $items->Amount, $cause);
        }
    }

    public function DecItemByItemID(int $itemID, int $amount, int $cause = ItemValue::CauseDefault, bool $forcheck = false): bool|array {

        if (isset($this->bagInfo->items->{ $itemID})) {
            $decItems = [];
            foreach ($this->bagInfo->items->{ $itemID} as $userItemID) {

                $userItemHandler = new UserItemHandler($userItemID);
                $userItemInfo = $userItemHandler->GetInfo();
                $decItem = new stdClass();
                $decItem->userItemID = $userItemID;

                if ($amount > $userItemInfo->amount) {
                    $decItem->amount = $userItemInfo->amount;
                    $amount -= $userItemInfo->amount;
                } else {
                    $decItem->amount = $amount;
                    $amount = 0;
                }

                $decItems[] = $decItem;
                if ($amount == 0) {
                    break;
                }
            }

            if ($amount > 0) {
                return false;
            }

            if ($forcheck) {
                return $decItems;
            } else {
                foreach ($decItems as $decItem) {
                    $this->DecItem($decItem->userItemID, $decItem->amount, $cause);
                }
                return true;
            }
        } else {
            $nowAmount = $this->GetCurrencyAmount($itemID);
            if ($amount > $nowAmount) {
                return false;
            }

            if ($forcheck) {
                $decItem = new stdClass();
                $decItem->userItemID = $itemID;
                $decItem->amount = $amount;
                $decItems[] = $decItem;
                return $decItems;
            } else {
                return $this->DecItem($itemID, $amount, $cause);
            }
        }
    }

    // $userItemID < 0 代表為 itemID, 
    // 因為 DecItemByItemID  $forcheck == true時，僅回調Item資訊
    public function DecItem(int $userItemID, int $amount, int $cause = ItemValue::CauseDefault): bool {
        if (($userItemID == 0) or ($amount <= 0)) {
            throw new Exception('The userItemID \'' . $userItemID . '\' or amount\'' . $amount . '\'  can not <= 0', ErrorCode::ParamError);
        }

        if ($userItemID < 0) {

            $userHandler = new UserHandler($this->userID);
            $info = $userHandler->GetInfo();
            $lastAmount = 0;
            //itemID 以 userItemID 暫代
            switch ($userItemID) {
                case ItemValue::CurrencyPower: //-1 電力
                    $lastAmount = $info->power - $amount;
                    $userHandler->SaveData(['power' => $lastAmount]);
                    break;
                case ItemValue::CurrencyCoin: //-2 金幣
                    $lastAmount = $info->coin - $amount;
                    $userHandler->SaveData(['coin' => $lastAmount]);
                    break;
                case ItemValue::CurrencyDiamond: //-3 寶石
                    $lastAmount = $info->diamond - $amount;
                    $userHandler->SaveData(['diamond' => $lastAmount]);
                    break;
                case ItemValue::CurrencyPetaToken: //-4 PT
                    $lastAmount = $info->petaToken - $amount;
                    $userHandler->SaveData(['petaToken' => $lastAmount]);
                    break;
                default :
                    return false;
            }
            $itemAccessor = new ItemAccessor();
            $itemAccessor->AddLog(0, $this->userID, $userItemID, $cause, ItemValue::ActionUsed, $amount, $lastAmount);
            return true;
        } else {

            $userItemHandler = new UserItemHandler($userItemID);
            $userItemInfo = $userItemHandler->GetInfo();

            if ($userItemInfo->user != $this->userID) {
                throw new ItemException(ItemException::UserNotItemOwner, ['[userItemID]' => $userItemID]);
            }
            return $userItemHandler->DecItem($amount, $cause);
        }
    }

    public function GetUserItemInfo(int $userItemID): UserItemHolder|stdClass {
        $userItemHandler = new UserItemHandler($userItemID);
        return $userItemHandler->GetInfo();
    }

    public function CheckItemEffectType(int $itemID, int $type): bool {
        $info = $this->itemInfoPool->{ $itemID};
        return $info->EffectType == $type;
    }

}
