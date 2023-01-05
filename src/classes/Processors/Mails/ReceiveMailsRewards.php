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

        if ($openStatus != 0)
            $openStatus = 1;

        // userMailID 相容新舊版 Client 解析單一信件(string) 或 多封信件(json)
        $temp = json_decode($userMailID);
        if (is_Array($temp) == true)
            $userMailIDs = $temp;
        else 
            $userMailIDs[] = $temp;

        $checkException = 0;
        $itemsArray = [];
        $accessor = new PDOAccessor(EnvVar::DBMain);
        $accessor->Transaction(function () use ($accessor, $userMailIDs, $openStatus, $receiveStatus, &$checkException, &$itemsArray) {
            $userID = $_SESSION[Sessions::UserID];
            $lockInfo = $accessor->FromTable('SystemLock')->
                            WhereEqual('UserID', $userID)->WhereEqual('APIName', $GLOBALS[Globals::REDIRECT_URL])->
                            ForUpdate()->Fetch();

            $userBagHandler = new UserBagHandler($userID);
            $userMailsHandler = new MailsHandler();
            $totalItems = [];
            $checkUserMailsIDs = [];
            
            $checkTotlaItems = [];
            foreach ($userMailIDs as $userMailID) {

                $mailInfo = $userMailsHandler->GetUserMailByUserMailID($userID, $userMailID);
                if ($mailInfo == false) {
                    $checkException = ItemException::MailNotExist;
                    continue;
                }
    
                if ($receiveStatus == 1) {
                    if ($mailInfo->ReceiveStatus == MailValues::ReceiveStatusDone) {
                        $checkException = ItemException::MailRewardsReceived;
                        continue;
                    }
    
                    $addItems = $userMailsHandler->GetUserMailItems($userMailID);
                    ItemUtility::AccumulateItems($checkTotlaItems, $addItems);

                    if ($userBagHandler->CheckAddStacklimit($checkTotlaItems) == false) {
                        $checkException = ItemException::UserItemStacklimitReached;
                        // 若此次信件道具數量超過上限 則回復暫存陣列
                        $checkTotlaItems = [];
                        ItemUtility::AccumulateItems($checkTotlaItems, $totalItems);
                        continue;
                    }

                    ItemUtility::AccumulateItems($totalItems, $addItems);
                }
    
                $checkUserMailsIDs[] = $userMailID;
            }

            // 全部信件 全部領獎 全部開信
            $itemsArray = [];
            if ($receiveStatus == 1) {     
                $userMailsHandler->ReceiveRewards($userID, $checkUserMailsIDs, $openStatus, MailValues::ReceiveStatusDone);
                $userBagHandler->AddItems($totalItems, ItemValue::CauseMail);            
                foreach ($totalItems as $item) {
                    $itemsArray[] = ItemUtility::GetClientSimpleInfo($item->ItemID, $item->Amount);
                }
            }
            else if ($receiveStatus == 0) {
                $userMailsHandler->UpdateOpenStatus($userID, $checkUserMailsIDs, $openStatus);
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
        $result->errorCode = $checkException;

        return $result;
    }

}
