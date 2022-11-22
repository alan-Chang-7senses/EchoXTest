<?php

namespace Games\Accessors;

use Consts\Globals;

class MailsAccessor extends BaseAccessor
{

    public function GetUserMails(int $userID): array
    {
        return $this->MainAccessor()->FromTable('UserMails')->WhereEqual('UserID', $userID)
            ->WhereGreater('FinishTime', $GLOBALS[Globals::TIME_BEGIN])
            ->FetchAll();
    }

    public function AddMail(int|string $userID, int $mailID, int $days, int $receiveStatus = 0): int
    {
        $this->MainAccessor()->FromTable('UserMails')->Add([
            'UserID' => $userID,
            'MailsID' => $mailID,
            'OpenStatus' => 0,
            'ReceiveStatus' => $receiveStatus,
            'CreateTime' => $GLOBALS[Globals::TIME_BEGIN],
            'UpdateTime' => $GLOBALS[Globals::TIME_BEGIN],
            'FinishTime' => $GLOBALS[Globals::TIME_BEGIN] + 86400 * $days,
        ]);

        return (int)$this->MainAccessor()->FromTable('UserMails')->LastInsertID();
    }

    public function UpdateUserMails(int $userMailID, array $bind): bool
    {
        $bind['UpdateTime'] = $GLOBALS[Globals::TIME_BEGIN];
        return $this->MainAccessor()->FromTable('UserMails')->WhereEqual('UserMailID', $userMailID)->Modify($bind);
    }


    public function GetUnreadMails(string $userID): int
    {   
        return $this->MainAccessor()->SelectExpr('COUNT(*) AS cnt')->FromTable('UserMails')
                ->WhereEqual('UserID', $userID)->WhereGreater('FinishTime', $GLOBALS[Globals::TIME_BEGIN])->WhereEqual('OpenStatus', 0)
                ->Fetch()->cnt;
    }

    public function GetUserMailItems(int $userMailID): array
    {
        return $this->MainAccessor()->FromTable('UserMailItems')->WhereEqual('UserMailID', $userMailID)->FetchAll();
    }

    public function AddUserMailItems(int $userMailID, int $itemID, int $amount): bool
    {
        return $this->MainAccessor()->FromTable('UserMailItems')->Add([
            'UserMailID' => $userMailID,
            'ItemID' => $itemID,
            'Amount' => $amount,
        ]);
    }

    public function GetMailsInfo(int $mailID): array
    {
        return $this->StaticAccessor()->FromTable('MailsInfo')->WhereEqual('MailsID', $mailID)->FetchAll();
    }

}
