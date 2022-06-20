<?php

namespace Processors\Mails;

use Processors\BaseProcessor;
use Consts\ErrorCode;
use Holders\ResultData;
use Helpers\InputHelper;
use Games\Mails\MailsHandler;

class ReceiveMailsRewards extends BaseProcessor{

    public function Process(): ResultData {
    
    
        $mailsID = InputHelper::post('mailsID');
        $openStatus = InputHelper::post('openStatus');
        $receiveStatus = InputHelper::post('receiveStatus');

        //TODO 道具放進包包
        if($receiveStatus  == 1)
        {

        }


        $userMailsHandler = new MailsHandler();
        $userMailsHandler->ReceiveRewards($mailsID,$openStatus,$receiveStatus);

        $result = new ResultData(ErrorCode::Success);
    
        return $result;
    }


}