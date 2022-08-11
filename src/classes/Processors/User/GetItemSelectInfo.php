<?php

namespace Processors\User;

use Consts\Sessions;
use Consts\ErrorCode;
use Holders\ResultData;
use Helpers\InputHelper;
use Games\Users\ItemUtility;
use Processors\BaseProcessor;
use Games\Users\RewardHandler;
use Games\Users\UserBagHandler;
use Games\Exceptions\UserException;

class GetItemSelectInfo extends BaseProcessor
{


    public function Process(): ResultData
    {
        $userItemID = json_decode(InputHelper::post('userItemID'));
        $userid = $_SESSION[Sessions::UserID];
        $bagHandler = new UserBagHandler($userid);

        $itemInfo = $bagHandler->GetUserItemInfo($userItemID);
        if (($itemInfo->useType != 2) || ($itemInfo->rewardID == 0)) {
            throw new UserException(UserException::UseItemError, ['[itemID]' => $itemInfo->itemID]);
        }
        $rewardHandler = new RewardHandler($itemInfo->rewardID);
        $itemsArray = $rewardHandler->GetItems();

        $itemInfos = [];
        foreach ($itemsArray as $item) {
            $itemInfos[] = ItemUtility::GetClientSimpleInfo($item->ItemID, $item->Amount);
        }

        $result = new ResultData(ErrorCode::Success);
        $result->items = $itemInfos;

        return $result;
    }
}