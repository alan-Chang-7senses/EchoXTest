<?php

namespace Games\Mails;

use stdClass;
use Games\Mails\Holders\MailsHolder;
use Games\Pools\UserMailsPool;
use Games\Pools\MailsInfoPool;
use Games\Pools\MailsRewardPool;
use Games\Accessors\MailsAccessor;
use Consts\Globals;
use Consts\Sessions;

class MailsHandler {

    private MailsHolder|stdClass $userMailsinfo;
    private MailsHolder|stdClass $mailsinfo;
    private MailsHolder|stdClass $mailsRewards;

    public function GetUserMailsInfo(int|string $id) : MailsHolder|stdClass{
        $this->userMailsinfo = UserMailsPool::Instance()->$id;
        return $this->userMailsinfo;
    }
    public function GetMailsInfo(int $mailID): MailsHolder|stdClass {
        $mailsAccessor = new MailsAccessor();
        $rows = $mailsAccessor->rowsMailsInfo($mailID);

        $holder = new stdClass();
        $holder->rows = $rows;
        
        $holder->data = [];
        foreach($rows as $row){
            $holder->data[$row->Lang] = $row;
        }
        $this->mailsinfo = $holder;
        //$this->mailsinfo = MailsInfoPool::Instance()->$mailID;
        return $this->mailsinfo;
    }
    public function GetMailsRewards(int $RewardID): MailsHolder|stdClass{
        $this->mailsRewards = MailsRewardPool::Instance()->$RewardID;
        return $this->mailsRewards;
    }
    public function ReceiveRewards(int $mailsID,int $openStatus, int $receiveStatus){ 
        $bind = [
            'OpenStatus' => $openStatus,
            'ReceiveStatus' => $receiveStatus,
            'UpdateTime' => $GLOBALS[Globals::TIME_BEGIN],
        ];
        $mailsAccessor = new MailsAccessor();
        $mailsAccessor->receiveMailsRewards($mailsID,$_SESSION[Sessions::UserID],$bind);
        UserMailsPool::Instance()->Delete($mailsID);

    }
    public function DeleteMails(int $mailsID){
        $bind = [
            'UpdateTime' => $GLOBALS[Globals::TIME_BEGIN],
            'FinishTime' => $GLOBALS[Globals::TIME_BEGIN],
        ];
        $mailsAccessor = new MailsAccessor();
        $mailsAccessor->deleteMails($mailsID,$_SESSION[Sessions::UserID],$bind);
        UserMailsPool::Instance()->Delete($mailsID);
    }
}