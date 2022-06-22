<?php

namespace Processors\Mails;

use Processors\BaseProcessor;
use Consts\ErrorCode;
use Holders\ResultData;
use Helpers\InputHelper;
use Games\Mails\MailsHandler;

class DeleteMails extends BaseProcessor{

    public function Process(): ResultData {
    
        $mailsID = InputHelper::post('mailsID');
        $userMailsHandler = new MailsHandler();
        $userMailsHandler->DeleteMails($mailsID);
    
        $result = new ResultData(ErrorCode::Success);
    
        return $result;
    }


}