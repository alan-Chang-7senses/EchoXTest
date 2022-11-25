<?php

namespace Processors\Mails;

use Consts\ErrorCode;
use Consts\Globals;
use Consts\Sessions;
use Games\Mails\MailsHandler;
use Games\Users\ItemUtility;
use Helpers\InputHelper;
use Holders\ResultData;
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
            
            $content =  $userMailsHandler->ReplaceContent( $mailInfo->Content,$userMailInfo->MailArgument);
            $mails[] = [
                'userMailID' =>$userMailInfo->UserMailID,
                'openStatus' => $userMailInfo->OpenStatus,
                'receiveStatus' => $userMailInfo->ReceiveStatus,
                'title' => $mailInfo->Title,
                'content' => $content,
                'argus' => $userMailInfo->MailArgument,
                'sender' => $mailInfo->Sender,
                'url' => $mailInfo->URL,
                'remainingTime' => $userMailInfo->FinishTime - $GLOBALS[Globals::TIME_BEGIN],
                'rewardItems' => $items,
            ];
        }
        $result = new ResultData(ErrorCode::Success);
        $result->Mails = $mails;

        return $result;
    }
} 
