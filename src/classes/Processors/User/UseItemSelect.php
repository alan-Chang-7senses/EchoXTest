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
        if (($itemInfo->useType != 2) || ($itemInfo->rewardID == 0)) {
            throw new UserException(UserException::UseItemError, ['[itemID]' => $itemInfo->itemID]);
        }

        $rewardHandler = new RewardHandler($itemInfo->rewardID);
        if (($rewardHandler->CheckSelectIndex($selectIndex) == false) || ($amount <= 0)) {
            return new ResultData(ErrorCode::ParamError);
        }

        if ($bagHandler->DecItem($userItemID, $amount) == false) {
            throw new UserException(UserException::UseItemError, ['[itemID]' => $itemInfo->itemID]);
        }


        $addItem = $rewardHandler->AddSelectReward($userid, $amount, $selectIndex);

        $itemInfoPool = ItemInfoPool::Instance();
        $itemInfo = $itemInfoPool->{ $addItem->ItemID};
        $result = new ResultData(ErrorCode::Success);
        $result->itemID = $addItem->ItemID;
        $result->amount = $addItem->Amount;
        $result->icon = $itemInfo->Icon;

        return $result;
    }
}