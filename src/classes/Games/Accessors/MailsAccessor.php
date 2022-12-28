<?php

namespace Games\Accessors;

use Consts\Globals;
use Games\Consts\MailValues;
use Generators\ConfigGenerator;

class MailsAccessor extends BaseAccessor {

    public function GetUserMails(int $userID): array {
        $limits = ConfigGenerator::Instance()->MailShowLimit;

        return $this->MainAccessor()->FromTable('UserMails')
                        ->WhereEqual('UserID', $userID)->WhereGreater('FinishTime', $GLOBALS[Globals::TIME_BEGIN])
                        ->OrderBy('CreateTime', 'DESC')
                        ->Limit($limits)->FetchAll();
    }

    public function AddMailWithDays(int|string $userID, int $mailID, int $days, array $arguments, int $receiveStatus = 0): int {
        $createTime = (int)$GLOBALS[Globals::TIME_BEGIN];

        $finishTime = 0;
        if ($days < 0)
            $finishTime = MailValues::MailMaxTimestamp;
        else
            $finishTime = $createTime + 86400 * $days;

        return self::AddMail($userID, $mailID, $createTime, $finishTime, $arguments, $receiveStatus);
    }

    public function AddMailWithTime(int|string $userID, int $mailID, int $startTime, int $endTime, array $arguments, int $receiveStatus = 0): int {

        return self::AddMail($userID, $mailID, $startTime, $endTime, $arguments, $receiveStatus);
    }

    private function AddMail(int|string $userID, int $mailID, int $createTime, int $finishTime, array $arguments, int $receiveStatus = 0): int {
        $this->MainAccessor()->FromTable('UserMails')->Add([
            'UserID' => $userID,
            'MailsID' => $mailID,
            'MailArgument' => json_encode($arguments),
            'OpenStatus' => 0,
            'ReceiveStatus' => $receiveStatus,
            'CreateTime' => $createTime,
            'UpdateTime' => $GLOBALS[Globals::TIME_BEGIN],
            'FinishTime' => $finishTime,
        ]);

        return (int) $this->MainAccessor()->FromTable('UserMails')->LastInsertID();
    }

    public function UpdateUserMails(array $userMailIDs, array $bind): bool {
        $bind['UpdateTime'] = $GLOBALS[Globals::TIME_BEGIN];
        return $this->MainAccessor()->FromTable('UserMails')->WhereIn('UserMailID', array_values($userMailIDs))->Modify($bind);
    }

    public function GetUnreadMails(string $userID): int {
        return $this->MainAccessor()->SelectExpr('COUNT(*) AS cnt')->FromTable('UserMails')
                        ->WhereEqual('UserID', $userID)->WhereGreater('FinishTime', $GLOBALS[Globals::TIME_BEGIN])->WhereEqual('OpenStatus', 0)
                        ->Fetch()->cnt;
    }

    public function GetUserMailItems(int $userMailID): array {
        return $this->MainAccessor()->FromTable('UserMailItems')->WhereEqual('UserMailID', $userMailID)->FetchAll();
    }

    public function AddUserMailItems(int $userMailID, int $itemID, int $amount): bool {
        return $this->MainAccessor()->FromTable('UserMailItems')->Add([
                    'UserMailID' => $userMailID,
                    'ItemID' => $itemID,
                    'Amount' => $amount,
        ]);
    }

    public function GetMailsInfo(int $mailID): array {
        return $this->StaticAccessor()->FromTable('MailsInfo')->WhereEqual('MailsID', $mailID)->FetchAll();
    }

    public function GetMailsItems(int $mailID): mixed {
        return $this->StaticAccessor()->FromTable('MailsItems')->WhereEqual('MailsID', $mailID)->Fetch();
    }

}
