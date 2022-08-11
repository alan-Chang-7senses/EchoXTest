<?php

namespace Processors\Mails;

use Consts\Sessions;
use Consts\ErrorCode;
use Holders\ResultData;
use Helpers\InputHelper;
use Games\Mails\MailsHandler;
use Games\Pools\ItemInfoPool;
use Processors\BaseProcessor;
use Games\Users\UserBagHandler;
use Games\Exceptions\UserException;

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
            throw new UserException(UserException::MailNotExist);
        }

        if ($openStatus != 0)
            $openStatus = 1;
        $itemsArray = [];
        if ($receiveStatus == 1) {
            if ($mailInfo->ReceiveStatus == 1) {
                throw new UserException(UserException::MailRewardsReceived);
            }

            $items = $userMailsHandler->GetMailItems($userMailID);
            $userBagHandler = new UserBagHandler($_SESSION[Sessions::UserID]);
            if ($userBagHandler->CheckAddStacklimit($items) == false) {
                throw new UserException(UserException::UserItemStacklimitReached);
            }
            foreach ($items as $item) {
                $userBagHandler->AddItem($item->ItemID, $item->Amount);

                $itemInfo = ItemInfoPool::Instance()->{ $item->ItemID};
                $itemsArray[] = [
                    'itemID' => $item->ItemID,
                    'amount' => $item->Amount,
                    'icon' => $itemInfo->Icon,
                ];
            }
            $userMailsHandler->ReceiveRewards($_SESSION[Sessions::UserID], $userMailID, $openStatus, 1);
        }
        else {
            $userMailsHandler->UpdateOpenStatus($_SESSION[Sessions::UserID], $userMailID, $openStatus);
        }

        $result = new ResultData(ErrorCode::Success);
        $result->addItems = $itemsArray;
        return $result;
    }


}