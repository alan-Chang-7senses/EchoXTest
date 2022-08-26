<?php

namespace Processors\Mails;

use Consts\Sessions;
use Consts\ErrorCode;
use Games\Consts\ItemValue;
use Holders\ResultData;
use Helpers\InputHelper;
use Games\Users\ItemUtility;
use Games\Mails\MailsHandler;
use Processors\BaseProcessor;
use Games\Users\UserBagHandler;
use Games\Exceptions\ItemException;

class ReceiveMailsRewards extends BaseProcessor
{

    public function Process(): ResultData
    {
        $userMailID = InputHelper::post('userMailID');
        $openStatus = InputHelper::post('openStatus');
        $receiveStatus = InputHelper::post('receiveStatus');

        $userMailsHandler = new MailsHandler();
        $mailInfo = $userMailsHandler->GetUserMailByuUerMailID($_SESSION[Sessions::UserID], $userMailID);
        if ($mailInfo == false) {
            throw new ItemException(ItemException::MailNotExist);
        }
        if ($openStatus != 0)
            $openStatus = 1;

        $itemsArray = [];
        if ($receiveStatus == 1) {
            if ($mailInfo->ReceiveStatus == 1) {
                throw new ItemException(ItemException::MailRewardsReceived);
            }

            $items = $userMailsHandler->GetMailItems($userMailID);
            $userBagHandler = new UserBagHandler($_SESSION[Sessions::UserID]);
            if ($userBagHandler->CheckAddStacklimit($items) == false) {
                throw new ItemException(ItemException::UserItemStacklimitReached);
            }
            foreach ($items as $item) {
                $userBagHandler->AddItems($item, ItemValue::CauseMail);
                $itemsArray[] = ItemUtility::GetClientSimpleInfo($item->ItemID, $item->Amount);
            }
            $userMailsHandler->ReceiveRewards($_SESSION[Sessions::UserID], $userMailID, $openStatus, 1);
        }
        else {
            $receiveStatus = $mailInfo->ReceiveStatus;
            $userMailsHandler->UpdateOpenStatus($_SESSION[Sessions::UserID], $userMailID, $openStatus);
        }

        $result = new ResultData(ErrorCode::Success);
        $result->openStatus = $openStatus;
        $result->receiveStatus = $receiveStatus;
        $result->rewardItems = $itemsArray;
        return $result;
    }


}