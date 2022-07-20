<?php

namespace Games\Accessors;

use Consts\Globals;

class MailsAccessor extends BaseAccessor{
    
    public function rowsMails(int $userID) : array {
        return $this->MainAccessor()->FromTable('UserMails')
                ->WhereEqual('UserID', $userID)->FetchAll();
    }
    public function rowsMailsInfo(int $mailID) : array {
        return $this->StaticAccessor()->FromTable('MailsInfo')
                ->WhereEqual('MailsID', $mailID)->FetchAll();
    }
    public function rowsMailsRewards(int $mailID) : array {
        return $this->StaticAccessor()->FromTable('MailsRewards')
                ->WhereEqual('RewardID', $mailID)->FetchAll();
    }
    public function receiveMailsRewards(int $mailsID,int|string $userID, array $bind) : bool{
        return $this->MainAccessor()->FromTable('UserMails')->WhereEqual('UserID', $userID)->WhereEqual('MailsID', $mailsID)->Modify($bind);
    }
    public function deleteMails(int $mailsID,int|string $userID, array $bind) : bool{
        return $this->MainAccessor()->FromTable('UserMails')->WhereEqual('UserID', $userID)->WhereEqual('MailsID', $mailsID)->Modify($bind);
    }
    public function getUnreadMails(string $userID): int
    {
        return count($this->MainAccessor()->FromTable('UserMails')
            ->WhereEqual('UserID', $userID)->WhereGreater('MailsID', 0)
            ->WhereGreater('FinishTime', $GLOBALS[Globals::TIME_BEGIN])
            ->WhereEqual('OpenStatus', 0)
            ->FetchAll());
    }
}
