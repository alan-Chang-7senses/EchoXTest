<?php

namespace Processors\User;

use stdClass;
use Consts\Sessions;
use Consts\ErrorCode;
use Holders\ResultData;
use Helpers\InputHelper;
use Generators\DataGenerator;
use Processors\BaseProcessor;
use Games\Users\RewardHandler;
use Games\Users\UserBagHandler;
use Games\Exceptions\UserException;

class UseItems extends BaseProcessor
{
    public function Process(): ResultData
    {
        $items = json_decode(InputHelper::post('items'));
        DataGenerator::ExistProperties($items[0], ['userItemID', 'amount']);
        $userid = $_SESSION[Sessions::UserID];
        $bagHandler = new UserBagHandler($userid);
        $rewardHandler = new RewardHandler();
        $totalAddItems = [];
        foreach ($items as $useItem) {

            if ($useItem->amount <= 0) {
                continue;
            }

            $itemInfo = $bagHandler->GetUserItemInfo($useItem->userItemID);
            if (($itemInfo->useType != 1) || ($itemInfo->rewardID == 0)) {
                throw new UserException(UserException::UseItemError, ['[itemID]' => $itemInfo->itemID]);
            }

            if ($bagHandler->DecItem($useItem->userItemID, $useItem->amount) == false) {
                throw new UserException(UserException::UseItemError, ['[itemID]' => $itemInfo->itemID]);
            }

            for ($i = 0; $i < $useItem->amount; $i++) {
                $addItems = $rewardHandler->AddReward($userid, $itemInfo->rewardID);
                foreach ($addItems as $addItem) {
                    $totalAddItems[$addItem->ItemID] += $addItem->Amount;
                }
            }
        }

        $itemsArray = [];
        foreach ($totalAddItems as $key => $value) {
            $item = new stdClass();
            $item->ItemID = $key;
            $item->Amount = $value;
            $itemsArray[] = $item;
        }

        $result = new ResultData(ErrorCode::Success);
        $result->addItems = $itemsArray;

        return $result;

    }


}