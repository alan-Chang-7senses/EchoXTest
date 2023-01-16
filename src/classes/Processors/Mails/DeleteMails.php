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
        $userMailID = InputHelper::post('userMailID');

        // userMailID 相容新舊版 Client 解析單一信件(string) 或 多封信件(json)
        $temp = json_decode($userMailID);
        if (is_Array($temp) == true)
            $userMailIDs = $temp;
        else
            $userMailIDs[] = $temp;

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

            $mailItems = $userMailsHandler->GetUserMailItems($userMailID);
            if ($mailItems !== false && count($mailItems) > 0 && $mailInfo->ReceiveStatus == 0) {
                $checkException = ItemException::DeleteMailAfterReceiveReward;
                continue;
            }
            
            $checkUserMailsIDs[] = $userMailID;
        }

        if (count($checkUserMailsIDs) != 0)
        {
            $userMailsHandler->DeleteMails($_SESSION[Sessions::UserID], $checkUserMailsIDs);
        }

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
