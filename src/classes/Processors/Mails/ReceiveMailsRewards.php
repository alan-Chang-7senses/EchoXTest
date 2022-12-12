<?php

namespace Processors\Mails;

use Consts\ErrorCode;
use Consts\Sessions;
use Games\Consts\ItemValue;
use Games\Consts\MailValues;
use Games\Exceptions\ItemException;
use Games\Mails\MailsHandler;
use Games\Users\ItemUtility;
use Games\Users\UserBagHandler;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\BaseProcessor;

class ReceiveMailsRewards extends BaseProcessor {

    public function Process(): ResultData {
        $userMailIDs = json_decode(InputHelper::post('userMailIDs'));
        $openStatus = InputHelper::post('openStatus');
        $receiveStatus = InputHelper::post('receiveStatus');

        if ($openStatus != 0)
            $openStatus = 1;

        $userID = $_SESSION[Sessions::UserID];
        $userBagHandler = new UserBagHandler($userID);
        $userMailsHandler = new MailsHandler();
        $totalItems = [];
        foreach ($userMailIDs as $userMailID) {

            $mailInfo = $userMailsHandler->GetUserMailByuUerMailID($userID, $userMailID);
            if ($mailInfo == false) {
                throw new ItemException(ItemException::MailNotExist);
            }

            if ($receiveStatus == 1) {
                if ($mailInfo->ReceiveStatus == MailValues::ReceiveStatusDone) {
                    throw new ItemException(ItemException::MailRewardsReceived);
                }
                $addItems = $userMailsHandler->GetMailItems($userMailID);
                if ($userBagHandler->CheckAddStacklimit($addItems) == false) {
                    throw new ItemException(ItemException::UserItemStacklimitReached);
                }
                ItemUtility::AccumulateItems($totalItems, $addItems);
                //$userMailsHandler->ReceiveRewards($userID, $userMailID, $openStatus, MailValues::ReceiveStatusDone);
            } else {
                //$receiveStatus = $mailInfo->ReceiveStatus;
                $userMailsHandler->UpdateOpenStatus($userID, $userMailID, $openStatus);
            }
        }

        $itemsArray = [];
        if ($receiveStatus == 1) {
            foreach ($userMailIDs as $userMailID) {
                $userMailsHandler->ReceiveRewards($userID, $userMailID, $openStatus, MailValues::ReceiveStatusDone);
            }           
            $userBagHandler->AddItems($totalItems, ItemValue::CauseMail);            
            foreach ($totalItems as $item) {
                $itemsArray[] = ItemUtility::GetClientSimpleInfo($item->ItemID, $item->Amount);
            }
        }

        $result = new ResultData(ErrorCode::Success);
        $result->openStatus = $openStatus;
        $result->receiveStatus = $receiveStatus;
        $result->rewardItems = $itemsArray;
        return $result;
    }

}
