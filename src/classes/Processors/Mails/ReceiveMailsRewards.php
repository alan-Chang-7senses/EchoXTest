<?php

namespace Processors\Mails;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\Globals;
use Consts\Sessions;
use Games\Consts\ItemValue;
use Games\Consts\MailValues;
use Games\Exceptions\ItemException;
use Games\Mails\MailsHandler;
use Games\Users\ItemUtility;
use Games\Users\UserBagHandler;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\BaseProcessor;

class ReceiveMailsRewards extends BaseProcessor {

    public function Process(): ResultData {

        $repeatCount = MailValues::MaxRepeatCount;
        while (true) {
            usleep(MailValues::RepeatTime);
            $repeatCount--;
            if ($repeatCount < 0) {
                throw new ItemException(ItemException::MailNotExist);
            }

            try {
                if ($this->GetRepeatFlag() === MailValues::Lock) {
                    continue;
                }

                $userMailID = InputHelper::post('userMailID');
                $openStatus = InputHelper::post('openStatus');
                $receiveStatus = InputHelper::post('receiveStatus');

                $userMailsHandler = new MailsHandler();
                $mailInfo = $userMailsHandler->GetUserMailByuUerMailID($_SESSION[Sessions::UserID], $userMailID);
                if ($mailInfo == false) {
                    throw new ItemException(ItemException::MailNotExist);
                }
                if ($openStatus != 0)
                    $openStatus = 1;

                $itemsArray = [];
                if ($receiveStatus == 1) {
                    if ($mailInfo->ReceiveStatus == MailValues::ReceiveStatusDone) {
                        throw new ItemException(ItemException::MailRewardsReceived);
                    }

                    $items = $userMailsHandler->GetMailItems($userMailID);
                    $userBagHandler = new UserBagHandler($_SESSION[Sessions::UserID]);

                    if ($userBagHandler->CheckAddStacklimit($items) == false) {
                        throw new ItemException(ItemException::UserItemStacklimitReached);
                    }
                    foreach ($items as $item) {
                        $userBagHandler->AddItems($item, ItemValue::CauseMail);
                        $itemsArray[] = ItemUtility::GetClientSimpleInfo($item->ItemID, $item->Amount);
                    }
                    $userMailsHandler->ReceiveRewards($_SESSION[Sessions::UserID], $userMailID, $openStatus, MailValues::ReceiveStatusDone);
                } else {
                    $receiveStatus = $mailInfo->ReceiveStatus;
                    $userMailsHandler->UpdateOpenStatus($_SESSION[Sessions::UserID], $userMailID, $openStatus);
                }
                $this->ReleaseLockFlag();
                break;
            } catch (\Exception $ex) {
                $this->ReleaseLockFlag();
                throw $ex;
            }
        }

        $result = new ResultData(ErrorCode::Success);
        $result->openStatus = $openStatus;
        $result->receiveStatus = $receiveStatus;
        $result->rewardItems = $itemsArray;

        return $result;
    }

    private function GetRepeatFlag(): int {
        $accessor = new PDOAccessor(EnvVar::DBMain);
        $delayFlag = MailValues::UnLock;
        $accessor->Transaction(function () use ($accessor, &$delayFlag) {
            $userID = $_SESSION[Sessions::UserID];
            $info = $accessor->FromTable('SystemLock')->
                            WhereEqual('UserID', $userID)->WhereEqual('APIName', $GLOBALS[Globals::REDIRECT_URL])->
                            ForUpdate()->Fetch();

            if (empty($info)) {
                $delayFlag = MailValues::UnLock;
                $accessor->Add([
                    "UserID" => $userID,
                    "APIName" => $GLOBALS[Globals::REDIRECT_URL],
                    "LockFlag" => MailValues::Lock,
                    "UpdateTime" => $GLOBALS[Globals::TIME_BEGIN],
                ]);
            } else {

                $delayFlag = $info->LockFlag;

                if ($delayFlag == MailValues::UnLock) {
                    $accessor->Modify([
                        "LockFlag" => MailValues::Lock,
                        "UpdateTime" => $GLOBALS[Globals::TIME_BEGIN],
                    ]);
                }
            }
        });
        return $delayFlag;
    }

    private function ReleaseLockFlag() {
        $accessor = new PDOAccessor(EnvVar::DBMain);
        $accessor->Transaction(function () use ($accessor) {
            $userID = $_SESSION[Sessions::UserID];
            $info = $accessor->FromTable('SystemLock')->WhereEqual("UserID", $userID)->WhereEqual("APIName", $GLOBALS[Globals::REDIRECT_URL])->ForUpdate()->Fetch();
            if (!empty($info)) {
                $accessor->Modify([
                    "LockFlag" => MailValues::UnLock,
                    "UpdateTime" => $GLOBALS[Globals::TIME_BEGIN],
                ]);
            }
        });
    }

}
