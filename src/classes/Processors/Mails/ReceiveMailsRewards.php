<?php

namespace Processors\Mails;

use Processors\BaseProcessor;
use Consts\ErrorCode;
use Holders\ResultData;
use Helpers\InputHelper;
use Consts\Sessions;
use Games\Mails\MailsHandler;
use Consts\Globals;
use Games\Users\UserBagHandler;

class ReceiveMailsRewards extends BaseProcessor{

    public function Process(): ResultData {
    
    
        $mailsIDs = InputHelper::post('mailsID');
        $lang = InputHelper::post('lang');
        $openStatus = InputHelper::post('openStatus');
        $receiveStatus = InputHelper::post('receiveStatus');

        //TODO 道具放進包包
        if($receiveStatus  == 1)
        {
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
                    if($mailInfoData[$i]->RewardID == $mailsRewards->rows[$j]->RewardID && $userMailsInfo->rows[$i]->MailsID == $mailsIDs){
                        $bagHandler = new UserBagHandler($_SESSION[Sessions::UserID]);
                        $bagHandler->AddItem($_SESSION[Sessions::UserID],$mailsRewards->rows[$j]->ItemID1,$mailsRewards->rows[$j]->ItemNumber1);
                        if($mailsRewards->rows[$j]->ItemID2==null || $mailsRewards->rows[$j]->ItemID2 == '')
                            continue;
                        $bagHandler->AddItem($_SESSION[Sessions::UserID],$mailsRewards->rows[$j]->ItemID2,$mailsRewards->rows[$j]->ItemNumber2);
                        if($mailsRewards->rows[$j]->ItemID3==null || $mailsRewards->rows[$j]->ItemID3 == '')
                            continue;
                        $bagHandler->AddItem($_SESSION[Sessions::UserID],$mailsRewards->rows[$j]->ItemID3,$mailsRewards->rows[$j]->ItemNumber3);    
                    }
                }
            }
        }


        $userMailsHandler = new MailsHandler();
        $userMailsHandler->ReceiveRewards($mailsIDs,$openStatus,$receiveStatus);

        $result = new ResultData(ErrorCode::Success);
    
        return $result;
    }


}