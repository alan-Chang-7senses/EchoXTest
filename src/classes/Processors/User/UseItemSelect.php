<?php

namespace Processors\User;

use Consts\ErrorCode;
use Consts\Sessions;
use Games\Consts\ItemValue;
use Games\Consts\RewardValue;
use Games\Exceptions\ItemException;
use Games\Pools\ItemInfoPool;
use Games\Users\RewardHandler;
use Games\Users\UserBagHandler;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\BaseProcessor;

class UseItemSelect extends BaseProcessor
{
    public function Process(): ResultData
    {
        $userItemID = json_decode(InputHelper::post('userItemID'));
        $amount = json_decode(InputHelper::post('amount'));
        $selectIndex = json_decode(InputHelper::post('selectIndex'));

        $userid = $_SESSION[Sessions::UserID];
        $bagHandler = new UserBagHandler($userid);
        $itemInfo = $bagHandler->GetUserItemInfo($userItemID);
        if (($itemInfo->useType != ItemValue::UseChoose) || ($itemInfo->rewardID == 0)) {
            throw new ItemException(ItemException::UseItemError, ['[itemID]' => $itemInfo->itemID]);
        }

        $rewardHandler = new RewardHandler($itemInfo->rewardID);
        if (($rewardHandler->GetInfo()->Modes != RewardValue::ModeSelfSelect)) {
            throw new ItemException(ItemException::UseItemError, ['[itemID]' => $itemInfo->itemID]);
        }

        $addItem = $rewardHandler->GetSelectReward($selectIndex);
        if (($addItem == false) || ($amount <= 0)) {
            throw new ItemException(ItemException::UseItemError, ['[itemID]' => $itemInfo->itemID]);
        }

        $addItem->Amount = $addItem->Amount * $amount;
        if ($bagHandler->CheckAddStacklimit($addItem) == false) {
            throw new ItemException(ItemException::UserItemStacklimitReached, ['[itemID]' => $addItem->ItemID]);
        }

        if ($bagHandler->DecItem($userItemID, $amount, ItemValue::CauseUsed) == false) {
            throw new ItemException(ItemException::UseItemError, ['[itemID]' => $itemInfo->itemID]);
        }

        if ($bagHandler->AddItems($addItem, ItemValue::CauseUsed) == false) {
            throw new ItemException(ItemException::UseItemError, ['[itemID]' => $itemInfo->itemID]);
        }

        $itemInfoPool = ItemInfoPool::Instance();
        $itemInfo = $itemInfoPool->{ $addItem->ItemID};
        $result = new ResultData(ErrorCode::Success);
        $result->itemID = $addItem->ItemID;
        $result->amount = $addItem->Amount;
        $result->icon = $itemInfo->Icon;

        return $result;
    }
}