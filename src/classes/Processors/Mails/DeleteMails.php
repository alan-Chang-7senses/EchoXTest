<?php

namespace Processors\Mails;

use Consts\ErrorCode;
use Consts\Sessions;
use Games\Exceptions\ItemException;
use Games\Mails\MailsHandler;
use Games\Pools\MailsItemsPool;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\BaseProcessor;

class DeleteMails extends BaseProcessor {

    public function Process(): ResultData {
        $userMailIDs = json_decode(InputHelper::post('userMailIDs'));

        $userMailsHandler = new MailsHandler();
        $checkUserMailsIDs = [];
        $checkException = 0;
        foreach ($userMailIDs as $userMailID) {

            $mailInfo = $userMailsHandler->GetUserMailByUserMailID($_SESSION[Sessions::UserID], $userMailID);
            if ($mailInfo == false) {
                $checkException = ItemException::MailNotExist;
                continue;
            }

            if ($mailInfo->OpenStatus == 0) {
                $checkException = ItemException::DeleteMailAfterOpenMail;
                continue;
            }

            $mailItems = MailsItemsPool::Instance()->{$mailInfo->MailsID};
            if ($mailItems !== false && $mailInfo->ReceiveStatus == 0) {
                $checkException = ItemException::DeleteMailAfterReceiveReward;
                continue;
            }
            
            $checkUserMailsIDs[] = $userMailID;
        }

        $userMailsHandler->DeleteMails($_SESSION[Sessions::UserID], $checkUserMailsIDs);

        if ($checkException == ItemException::MailNotExist)
            throw new ItemException(ItemException::MailNotExist);
        else if ($checkException == ItemException::DeleteMailAfterOpenMail)
            throw new ItemException(ItemException::DeleteMailAfterOpenMail);
        else if ($checkException == ItemException::DeleteMailAfterReceiveReward)
            throw new ItemException(ItemException::DeleteMailAfterReceiveReward);

        $result = new ResultData(ErrorCode::Success);
        
        return $result;
    }

}
