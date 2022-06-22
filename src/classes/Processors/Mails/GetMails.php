<?php

namespace Processors\Mails;

use Consts\ErrorCode;
use Holders\ResultData;
use Processors\BaseProcessor;
use Helpers\InputHelper;
use Consts\Sessions;
use Games\Mails\MailsHandler;
use Consts\Globals;

class GetMails extends BaseProcessor{
    
    public function Process(): ResultData {
        $lang = InputHelper::post('lang');

        $userMailsHandler = new MailsHandler();

        $userMailsInfo = $userMailsHandler->GetUserMailsInfo($_SESSION[Sessions::UserID]);
        $mailsID = [];
        for($i = 0; $i < count($userMailsInfo->rows); $i++){
            $mailsID[$i]=$userMailsInfo->rows[$i]->MailsID;
        }

        $mailInfoData = [];
        foreach($mailsID as $mailID){
            $mailsInfo = $userMailsHandler->GetMailsInfo($mailID);
            $mailInfoData[] = $mailsInfo->data[$lang];
        }

        $rewardsID = [];
        for($i = 0; $i < count($mailInfoData); $i++){
            if($mailInfoData[$i]->RewardID!=null)
                $rewardsID[$i]=$mailInfoData[$i]->RewardID;
        }
        
        foreach($rewardsID as $rewardID){
            $mailsRewards = $userMailsHandler->GetMailsRewards($rewardID);
        }


        $result = new ResultData(ErrorCode::Success);
        for($i = 0; $i < count($userMailsInfo->rows); $i++)
        {
            if($userMailsInfo->rows[$i]->FinishTime<=$GLOBALS[Globals::TIME_BEGIN] )
                continue;
            for($j = 0; $j < count($mailInfoData); $j++)
            {
                $time = $userMailsInfo->rows[$i]->FinishTime - $GLOBALS[Globals::TIME_BEGIN];
                if($mailInfoData[$i]->RewardID == $mailsRewards->rows[$j]->RewardID)
                $result->Mails[] = [
                    'mailsID' => $userMailsInfo->rows[$i]->MailsID,
                    'openStatus' => $userMailsInfo->rows[$i]->OpenStatus,
                    'receiveStatus' =>  $userMailsInfo->rows[$i]->ReceiveStatus,
                    'title' => $mailInfoData[$i]->Title,
                    'content' => $mailInfoData[$i]->Content,
                    'sender' => $mailInfoData[$i]->Sender,
                    'url' => $mailInfoData[$i]->URL,
                    'remainingTime' => $time,
                    'mailsRewards1' => $mailsRewards->rows[$j]->ItemID1,
                    'mailsRewards1Number1' =>$mailsRewards->rows[$j]->ItemNumber1,
                    'mailsRewards2' => $mailsRewards->rows[$j]->ItemID2,
                    'mailsRewards1Number2' =>$mailsRewards->rows[$j]->ItemNumber2,
                    'mailsRewards3' => $mailsRewards->rows[$j]->ItemID3,
                    'mailsRewards1Number3' =>$mailsRewards->rows[$j]->ItemNumber3,
                
                ];
            }
            
        }
        return $result;
    }
}
