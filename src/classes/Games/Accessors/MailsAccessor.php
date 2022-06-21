<?php

namespace Games\Accessors;

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
    public function receiveMailsRewards(int $mailsID,int|string $userID, array $bind){
        $this->MainAccessor()->FromTable('UserMails')->WhereEqual('UserID', $userID)->WhereEqual('MailsID', $mailsID)->Modify($bind);
    }
    public function deleteMails(int $mailsID,int|string $userID, array $bind){
        $this->MainAccessor()->FromTable('UserMails')->WhereEqual('UserID', $userID)->WhereEqual('MailsID', $mailsID)->Modify($bind);
    }
}