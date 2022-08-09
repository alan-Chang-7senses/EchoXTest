<?php

namespace Processors\User;

use Consts\Sessions;
use Consts\ErrorCode;
use Holders\ResultData;
use Helpers\InputHelper;
use Games\Pools\ItemInfoPool;
use Processors\BaseProcessor;
use Games\Users\RewardHandler;
use Games\Users\UserBagHandler;
use Games\Exceptions\UserException;

class UseItems extends BaseProcessor
{
    public function Process(): ResultData
    {

        $userItemID = json_decode(InputHelper::post('userItemID'));
        $amount = json_decode(InputHelper::post('amount'));
        if ($amount <= 0) {
            throw new UserException(UserException::ItemNotEnough);
        }

        $userid = $_SESSION[Sessions::UserID];
        $bagHandler = new UserBagHandler($userid);
        $totalAddItems = []; 

        $itemInfo = $bagHandler->GetUserItemInfo($userItemID);
        if (($itemInfo->useType != 1) || ($itemInfo->rewardID == 0)) {
            throw new UserException(UserException::UseItemError, ['[itemID]' => $itemInfo->itemID]);
        }

        if ($bagHandler->DecItem($userItemID, $amount) == false) {
            throw new UserException(UserException::UseItemError, ['[itemID]' => $itemInfo->itemID]);
        }

        for ($i = 0; $i < $amount; $i++) {
            $rewardHandler = new RewardHandler($itemInfo->rewardID);
            $addItems = $rewardHandler->AddReward($userid);
            foreach ($addItems as $addItem) {
                if (isset($totalAddItems[$addItem->ItemID])) {
                    $totalAddItems[$addItem->ItemID] += $addItem->Amount;
                }
                else {
                    $totalAddItems[$addItem->ItemID] = $addItem->Amount;
                }
            }
        } 

        $itemsArray = [];
        $itemInfoPool = ItemInfoPool::Instance();
        foreach ($totalAddItems as $itemID => $amount) {
            $itemInfo = $itemInfoPool->{ $itemID};
            $itemsArray[] = [
                'itemID' => $itemID,
                'amount' => $amount,
                'icon' => $itemInfo->Icon,
            ];
        }

        $result = new ResultData(ErrorCode::Success);
        $result->addItems = $itemsArray;

        return $result;

    }
}