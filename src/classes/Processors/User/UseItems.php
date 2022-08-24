<?php

namespace Processors\User;

use Consts\Sessions;
use Consts\ErrorCode;
use Games\Consts\ItemValue;
use Holders\ResultData;
use Helpers\InputHelper;
use Games\Users\ItemUtility;
use Games\Mails\MailsHandler;
use Processors\BaseProcessor;
use Games\Users\RewardHandler;
use Games\Users\UserBagHandler;
use Generators\ConfigGenerator;
use Games\Exceptions\ItemException;

class UseItems extends BaseProcessor
{
    public function Process(): ResultData
    {

        $userItemID = json_decode(InputHelper::post('userItemID'));
        $amount = json_decode(InputHelper::post('amount'));
        if ($amount <= 0) {
            throw new ItemException(ItemException::ItemNotEnough);
        }

        $userID = $_SESSION[Sessions::UserID];
        $bagHandler = new UserBagHandler($userID);
        $totalAddItems = [];

        $itemInfo = $bagHandler->GetUserItemInfo($userItemID);
        if (($itemInfo->useType != ItemValue::UseDirectly) || ($itemInfo->rewardID == 0)) {
            throw new ItemException(ItemException::UseItemError, ['[itemID]' => $itemInfo->itemID]);
        }

        if ($bagHandler->DecItem($userItemID, $amount, ItemValue::CauseUsed) == false) {
            throw new ItemException(ItemException::UseItemError, ['[itemID]' => $itemInfo->itemID]);
        }

        $itemsResponse = [];
        $rewardHandler = new RewardHandler($itemInfo->rewardID);
        for ($i = 0; $i < $amount; $i++) {
            $addItems = $rewardHandler->GetItems();
            foreach ($addItems as $addItem) {
                if (isset($totalAddItems[$addItem->ItemID])) {
                    $totalAddItems[$addItem->ItemID]->Amount += $addItem->Amount;
                }
                else {
                    $totalAddItems[$addItem->ItemID] = $addItem;
                }
            }
            $rewardHandler->ReSetItems();
        }

        $addMailItems = [];
        $mailsResponse = [];
        foreach ($totalAddItems as $addItem) {
            if ($bagHandler->AddItems($addItem, ItemValue::CauseUsed) == false) {
                $mailsResponse[] = ItemUtility::GetClientSimpleInfo($addItem->ItemID, $addItem->Amount);
                $addMailItems[] = $addItem;
            }
            else {
                $itemsResponse[] = ItemUtility::GetClientSimpleInfo($addItem->ItemID, $addItem->Amount);
            }
        }

        if (count($addMailItems) > 0) {
            $mailsHandler = new MailsHandler();
            $userMailID = $mailsHandler->AddMail($userID, ConfigGenerator::Instance()->ItemFullAddMailID, ConfigGenerator::Instance()->ItemFullAddMailIDay);
            $mailsHandler->AddMailItems($userMailID, $addMailItems);
        }

        $result = new ResultData(ErrorCode::Success);
        $result->addItems = $itemsResponse;
        $result->addMailItems = $mailsResponse;

        return $result;

    }
}
