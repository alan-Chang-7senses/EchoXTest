<?php

namespace Processors\User;

use Processors\BaseProcessor;
use Holders\ResultData;
use Consts\ErrorCode;
use Consts\Sessions;
use Games\Users\UserBagHandler;
use Games\Users\UserItemHandler;
/**
 * Description of Items
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class Items extends BaseProcessor{
    
    public function Process(): ResultData {
        
        
        $bagHandler = new UserBagHandler($_SESSION[Sessions::UserID]);
        $bagInfo = $bagHandler->GetInfo($_SESSION[Sessions::UserID]);
        
        
        $items = [];
        foreach($bagInfo->items as $userItemID){
            
            $userItemHandler = new UserItemHandler($userItemID);
            $userItemHandler->GetItemInfo($userItemID);
            $userItemInfo = $userItemHandler->info;
            
            if($userItemInfo->amount <= 0)
                continue;
            
            $items[] = [
                'id' => $userItemInfo->itemID,
                'amount' => $userItemInfo->amount,
                'itemType' => $userItemInfo->itemType,
                'icon' => $userItemInfo->icon,
            ];
        }
        
        $result = new ResultData(ErrorCode::Success);
        $result->items = $items;
        return $result;
    }
}
