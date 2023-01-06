<?php

namespace Processors\Mails;

use Consts\ErrorCode;
use Consts\Globals;
use Consts\Sessions;
use Games\Mails\MailsHandler;
use Games\Users\ItemUtility;
use Generators\ConfigGenerator;
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

        $limits = ConfigGenerator::Instance()->MailShowLimit;
        $showCount = 0;
        
        foreach ($userMailsInfo->rows as $userMailInfo) {
            $showCount++;
            if ($showCount > $limits) {
                break;
            }

            $mailInfo = $userMailsHandler->GetMailInfo($userMailInfo->MailsID, $lang);
            if ($mailInfo == false) {
                continue;
            }

            $mailItems = $userMailsHandler->GetUserMailItems($userMailInfo->UserMailID);
            $items = [];
            foreach($mailItems as $mailItem) {
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
                'sendTime' => $userMailInfo->CreateTime,
                'remainingTime' => $userMailInfo->FinishTime - $GLOBALS[Globals::TIME_BEGIN],
                'rewardItems' => $items,
            ];
        }

        $result = new ResultData(ErrorCode::Success);
        $result->Mails = $mails;
        $result->totalMailsCount = count($userMailsInfo->rows);
        return $result;
    }
} 
