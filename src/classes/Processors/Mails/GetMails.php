<?php

namespace Processors\Mails;

use stdClass;
use Consts\Globals;
use Consts\Sessions;
use Consts\ErrorCode;
use Holders\ResultData;
use Helpers\InputHelper;
use Games\Mails\MailsHandler;
use Games\Users\ItemUtility;
use Processors\BaseProcessor;

class GetMails extends BaseProcessor
{

    public function Process(): ResultData
    {
        $lang = InputHelper::post('lang');
        $userid = $_SESSION[Sessions::UserID];
        $userMailsHandler = new MailsHandler();
        $userMailsInfo = $userMailsHandler->GetUserMails($userid);
        $mails = [];

        foreach ($userMailsInfo->rows as $userMailInfo) {
            $mailInfo = $userMailsHandler->GetMailInfo($userMailInfo->MailsID, $lang);
            if ($mailInfo == false) {
                continue;
            }

            $mailItems = $userMailsHandler->GetMailItems($userMailInfo->UserMailID);
            $items = [];
            foreach($mailItems as $mailItem)
            {
                $items[] = ItemUtility::GetClientSimpleInfo($mailItem->ItemID, $mailItem->Amount);
            }


            $mail = new stdClass();
            $mail = [
                'userMailID' =>$userMailInfo->UserMailID,
                'openStatus' => $userMailInfo->OpenStatus,
                'receiveStatus' => $userMailInfo->ReceiveStatus,
                'title' => $mailInfo->Title,
                'content' => $mailInfo->Content,
                'sender' => $mailInfo->Sender,
                'url' => $mailInfo->URL,
                'remainingTime' => $userMailInfo->FinishTime - $GLOBALS[Globals::TIME_BEGIN],
                'rewardItems' => $items,
            ];
            $mails[] = $mail;
        }
        $result = new ResultData(ErrorCode::Success);
        $result->Mails = $mails;

        return $result;
    }
}
