<?php

namespace Processors\User;

use stdClass;
use Consts\Sessions;
use Consts\ErrorCode;
use Holders\ResultData;
use Helpers\InputHelper;
use Games\Pools\ItemInfoPool;
use Processors\BaseProcessor;
use Games\Users\UserBagHandler;
use Games\Exceptions\UserException;

class GetItemInfo extends BaseProcessor
{


    public function Process(): ResultData
    {

        $itemID = InputHelper::post('itemID');
        $itemInfoPool = ItemInfoPool::Instance();
        $itemInfo = $itemInfoPool->{ $itemID};
        if ($itemInfo == false) {
            throw new UserException(UserException::ItemNotExists, ['itemID' => $itemID]);
        }

        $userid = $_SESSION[Sessions::UserID];
        $userBagHandler = new UserBagHandler($userid);



        $item = new stdClass();
        $item->name = $itemInfo->ItemName;
        $item->description = $itemInfo->Description;
        $item->amount = $userBagHandler->GetItemAmount($itemID);
        $item->stackLimit = $itemInfo->StackLimit;
        $item->itemType = $itemInfo->ItemType;
        $item->useType = $itemInfo->UseType;
        $item->source = $itemInfo->Source;

        $result = new ResultData(ErrorCode::Success);
        $result->item = $item;
        return $result;
    }
}