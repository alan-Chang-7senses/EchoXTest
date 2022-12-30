<?php

namespace Games\Mails;

use Consts\Globals;
use Games\Accessors\MailsAccessor;
use Games\Consts\MailValues;
use Games\Pools\MailsInfoPool;
use Games\Pools\MailsItemsPool;
use Games\Users\RewardHandler;
use Games\Pools\UserMailItemsPool;
use Games\Pools\UserMailsPool;
use stdClass;

class MailsHandler {

    private array $arguments = [];

    public function GetUserMails(int|string $userID): stdClass {
        return UserMailsPool::Instance()->{ $userID};
    }

    public function GetUserMailByUserMailID(int|string $userID, int|string $userMailID): stdClass|false {
        $userMails = UserMailsPool::Instance()->$userID;
        foreach ($userMails->rows as $userMail) {
            if ($userMail->UserMailID == $userMailID) {
                return $userMail;
            }
        }
        return false;
    }

    public function ClearMailArgument() {
        $this->arguments = [];
    }

    public function AddMailArgument(int $kind, string $value) {
        if ($kind != MailValues::ArgumentNone) {
            $this->arguments[] = ["kind" => $kind, "value" => $value];
        }
    }

    public function AddMail(int|string $userID, int $mailID, int $day, int $receiveStatus = MailValues::ReceiveStatusNone): int {
        $mailsAccessor = new MailsAccessor();
        $index = $mailsAccessor->AddMailWithDays($userID, $mailID, $day, $this->arguments, $receiveStatus);
        UserMailsPool::Instance()->Delete($userID);
        return $index;
    }

    public function AddMailWithTime(int|string $userID, int $mailID, int $startTime, int $endTime, int $receiveStatus = MailValues::ReceiveStatusNone): int {
        $mailsAccessor = new MailsAccessor();
        $index = $mailsAccessor->AddMailWithTime($userID, $mailID, $startTime, $endTime, $this->arguments, $receiveStatus);
        UserMailsPool::Instance()->Delete($userID);
        return $index;
    }

    public function ReceiveRewards(int|string $userID, array $userMailIDs, int $openStatus, int $receiveStatus) {
        $bind = [
            'OpenStatus' => $openStatus,
            'ReceiveStatus' => $receiveStatus,
        ];
        $mailsAccessor = new MailsAccessor();
        $mailsAccessor->UpdateUserMails($userMailIDs, $bind);
        UserMailsPool::Instance()->Delete($userID);
    }

    public function UpdateOpenStatus(int|string $userID, array $userMailIDs, int $openStatus) {
        $bind = [
            'OpenStatus' => $openStatus
        ];
        $mailsAccessor = new MailsAccessor();
        $mailsAccessor->UpdateUserMails($userMailIDs, $bind);
        UserMailsPool::Instance()->Delete($userID);
    }

    public function DeleteMails(int|string $userID, array $userMailIDs) {
        $bind = [
            'UpdateTime' => $GLOBALS[Globals::TIME_BEGIN],
            'FinishTime' => $GLOBALS[Globals::TIME_BEGIN],
        ];
        $mailsAccessor = new MailsAccessor();
        $mailsAccessor->UpdateUserMails($userMailIDs, $bind);
        UserMailsPool::Instance()->Delete($userID);
    }

    public function GetUnreadMails(int|string $userID): int {
        $mailsAccessor = new MailsAccessor();
        return $mailsAccessor->getUnreadMails($userID);
    }

    public function AddMailItems(int $userMailID, array|stdclass $items): bool {
        $mailsAccessor = new MailsAccessor();
        if (is_array($items)) {
            foreach ($items as $item) {
                $mailsAccessor->AddUserMailItems($userMailID, $item->ItemID, $item->Amount);
            }
        } else {
            $mailsAccessor->AddUserMailItems($userMailID, $items->ItemID, $items->Amount);
        }

        UserMailItemsPool::Instance()->Delete($userMailID);
        return true;
    }

    public function GetUserMailItems(int $userMailID): array {
        $items = UserMailItemsPool::Instance()->{ $userMailID};
        return $items->rows;
    }

    public function GetMailInfo(int $mailsID, $lang): stdClass|false {
        $mailsInfo = MailsInfoPool::Instance()->$mailsID;
        if (isset($mailsInfo->{ $lang}) == false) {
            return false;
        }
        return $mailsInfo->{ $lang};
    }

    public function GetMailItems(int $mailsID): stdClass|false {
        $mailItems = MailsItemsPool::Instance()->$mailsID;

        $result = new stdClass();
        if ($mailItems !== false) {
            $rewardHandler = new RewardHandler($mailItems->RewardID);

            $result->MailsID = $mailItems->MailsID;
            $result->StartTime = $mailItems->StartTime;
            $result->EndTime = $mailItems->EndTime;
            $result->Items = [];
            foreach ($rewardHandler->GetItems() as $tempItem) {
                $item = new stdClass();
                $item->ItemID = $tempItem->ItemID;
                $item->Amount = $tempItem->Amount;
                $result->Items[] = $item;
            }
        }
        return $result; 
    }

    public function ReplaceContent(string $content, string|null|array &$argumentString): string {

        if (!empty($argumentString)) {
            $arguments = json_decode($argumentString);
            $response = [];
            foreach ($arguments as $argu) {

                switch ($argu->kind) {
                    case MailValues::ArgumentText:
                        $content = preg_replace(MailValues::ReplaceText, $argu->value, $content, 1);
                        continue 2;
                    case MailValues::ArgumentTime:
                        $response[] = ["kind" => MailValues::ClientTimeStamp, "value" => $argu->value];
                        break;
                    case MailValues::ArgumentAreaID:
                        $response[] = ["kind" => MailValues::ClientAreaID, "value" => $argu->value];
                        break;
                    case MailValues::ArgumentAmount:
                        $content = preg_replace(MailValues::ReplaceAmount, $argu->value, $content, 1);
                        continue 2;
                }
            }
            $argumentString = $response;
        }

        return $content;
    }

}
