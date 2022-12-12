<?php

namespace Processors\Mails;

use Consts\ErrorCode;
use Consts\Sessions;
use Games\Exceptions\ItemException;
use Games\Mails\MailsHandler;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\BaseProcessor;

class DeleteMails extends BaseProcessor {

    public function Process(): ResultData {
        $userMailIDs = json_decode(InputHelper::post('userMailIDs'));
        foreach ($userMailIDs as $userMailID) {

            $userMailsHandler = new MailsHandler();
            $mailInfo = $userMailsHandler->GetUserMailByuUerMailID($_SESSION[Sessions::UserID], $userMailID);
            if ($mailInfo == false) {
                throw new ItemException(ItemException::MailNotExist);
            }
            $userMailsHandler->DeleteMails($_SESSION[Sessions::UserID], $userMailID);
        }
        $result = new ResultData(ErrorCode::Success);
        return $result;
    }

}
