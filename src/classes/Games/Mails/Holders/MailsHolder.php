<?php
namespace Games\Mails\Holders;

use stdClass;

class MailsHolder extends stdClass{
    public int $userID;
    public int $mailsID;
    public int $openStatus;
    public int $receiveStatus;
    public int $createTime;
    public int $updateTime;
    public int $finishTime;
}
