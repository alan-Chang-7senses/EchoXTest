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

        $userMailID = InputHelper::post('userMailID');
        $openStatus = InputHelper::post('openStatus');
        $receiveStatus = InputHelper::post('receiveStatus');
        $itemsArray = [];
        $accessor = new PDOAccessor(EnvVar::DBMain);
        $accessor->Transaction(function () use ($accessor, $userMailID, $openStatus, $receiveStatus, &$itemsArray) {
            $userID = $_SESSION[Sessions::UserID];
            $lockInfo = $accessor->FromTable('SystemLock')->
                            WhereEqual('UserID', $userID)->WhereEqual('APIName', $GLOBALS[Globals::REDIRECT_URL])->
                            ForUpdate()->Fetch();

            $userMailsHandler = new MailsHandler();
            $mailInfo = $userMailsHandler->GetUserMailByuUerMailID($_SESSION[Sessions::UserID], $userMailID);
            if ($mailInfo == false) {
                throw new ItemException(ItemException::MailNotExist);
            }
            if ($openStatus != 0)
                $openStatus = 1;

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

            if (empty($lockInfo)) {
                $accessor->Add([
                    "UserID" => $userID,
                    "APIName" => $GLOBALS[Globals::REDIRECT_URL],
                    "LockFlag" => MailValues::UnLock,
                    "UpdateTime" => $GLOBALS[Globals::TIME_BEGIN],
                ]);
            } else {
                $accessor->Modify([
                    "UpdateTime" => $GLOBALS[Globals::TIME_BEGIN],
                ]);
            }
        });

        $result = new ResultData(ErrorCode::Success);
        $result->openStatus = $openStatus;
        $result->receiveStatus = $receiveStatus;
        $result->rewardItems = $itemsArray;

        return $result;
    }

}
