<?php

namespace Processors\Mails;

use Consts\Sessions;
use Consts\ErrorCode;
use Holders\ResultData;
use Helpers\InputHelper;
use Games\Mails\MailsHandler;
use Processors\BaseProcessor;
use Games\Exceptions\ItemException;

class DeleteMails extends BaseProcessor
{
    public function Process(): ResultData
    {

        $userMailID = InputHelper::post('userMailID');

        $userMailsHandler = new MailsHandler();
        $mailInfo = $userMailsHandler->GetUserMailByuUerMailID($_SESSION[Sessions::UserID], $userMailID);
        if ($mailInfo == false) {
            throw new ItemException(ItemException::MailNotExist);
        }
        $userMailsHandler->DeleteMails($_SESSION[Sessions::UserID], $userMailID);
        $result = new ResultData(ErrorCode::Success);
        return $result;
    }


}