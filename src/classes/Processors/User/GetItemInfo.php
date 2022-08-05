<?php

namespace Processors\User;

use stdClass;
use Consts\ErrorCode;
use Games\Exceptions\UserException;
use Holders\ResultData;
use Helpers\InputHelper;
use Games\Pools\ItemInfoPool;
use Processors\BaseProcessor;

class GetItemInfo extends BaseProcessor{


    public function Process(): ResultData {

        $itemID = InputHelper::post('itemID');
        $itemInfoPool = ItemInfoPool::Instance();
        $itemInfo = $itemInfoPool->{ $itemID};
        if ($itemInfo == false)
        {
            throw new UserException(UserException::ItemNotExists, ['itemID' => $itemID]);
        }

        $item = new stdClass();      
        $item->name = $itemInfo->ItemName;
        $item->description = $itemInfo->Description;
        $item->stackLimit = $itemInfo->StackLimit;        
        $item->itemType = $itemInfo->ItemType;
        $item->useType = $itemInfo->UseType;        
        $item->source = $itemInfo->Source;

        $result = new ResultData(ErrorCode::Success);
        $result->item = $item;
        return $result;
    }
}