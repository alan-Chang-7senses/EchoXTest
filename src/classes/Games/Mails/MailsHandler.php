<?php

namespace Games\Mails;

use stdClass;
use Consts\Globals;
use Games\Pools\MailsInfoPool;
use Games\Pools\UserMailsPool;
use Games\Accessors\MailsAccessor;
use Games\Pools\UserMailItemsPool;

class MailsHandler
{
    public function GetUserMails(int|string $userID): stdClass
    {
        return UserMailsPool::Instance()->{$userID};
    }

    public function GetUserMailByuUerMailID(int|string $userID, int|string $userMailID): stdClass |false
    {
        $userMailsInfo = UserMailsPool::Instance()->$userID;
        foreach($userMailsInfo->rows as $info)
        {
            if ($info->UserMailID == $userMailID)
            {
                return $info;
            }            
        }
        return false;
    }

    public function AddMail(int|string $userID, int $mailID, int $day): int
    {
        $mailsAccessor = new MailsAccessor();
        $index = $mailsAccessor->AddMail($userID, $mailID, $day);
        UserMailsPool::Instance()->Delete($userID);
        return $index;
    }

    public function ReceiveRewards(int|string $userID, int $userMailID, int $openStatus, int $receiveStatus)
    {
        $bind = [
            'OpenStatus' => $openStatus,
            'ReceiveStatus' => $receiveStatus,
        ];
        $mailsAccessor = new MailsAccessor();
        $mailsAccessor->UpdateUserMails($userMailID, $bind);
        UserMailsPool::Instance()->Delete($userID);
    }

    public function UpdateOpenStatus(int|string $userID, int $userMailID, int $openStatus)
    {
        $bind = [
            'OpenStatus' => $openStatus
        ];
        $mailsAccessor = new MailsAccessor();
        $mailsAccessor->UpdateUserMails($userMailID, $bind);
        UserMailsPool::Instance()->Delete($userID);
    }

    public function DeleteMails(int|string $userID, int $userMailID)
    {
        $bind = [
            'UpdateTime' => $GLOBALS[Globals::TIME_BEGIN],
            'FinishTime' => $GLOBALS[Globals::TIME_BEGIN],
        ];
        $mailsAccessor = new MailsAccessor();
        $mailsAccessor->UpdateUserMails($userMailID, $bind);
        UserMailsPool::Instance()->Delete($userID);
    }

    public function GetUnreadMails(int|string $userID): int
    {
        $mailsAccessor = new MailsAccessor();
        return $mailsAccessor->getUnreadMails($userID);
    }

    public function AddMailItems(int $userMailID, array $items): bool
    {
        $mailsAccessor = new MailsAccessor();
        foreach ($items as $item) {
            $mailsAccessor->AddUserMailItems($userMailID, $item->ItemID, $item->Amount);
        }

        UserMailItemsPool::Instance()->Delete($userMailID);
        return true;
    }

    public function GetMailItems(int $userMailID): array
    {
        $items = UserMailItemsPool::Instance()->{ $userMailID};
        return $items->rows;
    }


    public function GetMailInfo(int $mailsID, $lang): stdClass|false
    {
        $mailsInfo = MailsInfoPool::Instance()->$mailsID;
        if (isset($mailsInfo->{ $lang}) == false) {
            return false;
        }
        return $mailsInfo->{ $lang};
    }


}