<?php

namespace Processors\User;

use Consts\Sessions;
use Consts\ErrorCode;
use Holders\ResultData;
use Processors\BaseProcessor;
use Games\Users\UserBagHandler;

/**
 * Description of Items
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class Items extends BaseProcessor
{

    public function Process(): ResultData
    {
        $bagHandler = new UserBagHandler($_SESSION[Sessions::UserID]);
        $bagItemInfos = $bagHandler->GetItemInfos();
        $items = [];
        foreach ($bagItemInfos as $itemInfo) {

            if ($itemInfo->amount <= 0)
                continue;
                       
        
            $items[] = [
                'itemid' => $itemInfo->itemID,
                'userItemsID' =>$itemInfo->userItemsID,
                'amount' => $itemInfo->amount,
                'itemType' => $itemInfo->itemType,
                'icon' => $itemInfo->icon,
            ];           
        }

        $result = new ResultData(ErrorCode::Success);
        $result->items = $items;
        return $result;
    }
}
