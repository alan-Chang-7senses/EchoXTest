<?php

namespace Processors\User;

use Processors\BaseProcessor;
use Holders\ResultData;
use Consts\ErrorCode;
use Games\Users\UserHandler;
use Consts\Sessions;
use Games\Users\UserItemHandler;
/**
 * Description of Items
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class Items extends BaseProcessor{
    
    public function Process(): ResultData {
        
        $userHandler = new UserHandler($_SESSION[Sessions::UserID]);
        $userInfo = $userHandler->GetInfo();
        
        $items = [];
        foreach($userInfo->items as $userItemID){
            
            $userItemHandler = new UserItemHandler($userItemID);
            $userItemInfo = $userItemHandler->info;
            
            if($userItemInfo->amount <= 0)
                continue;
            
            $items[] = [
                'id' => $userItemInfo->id,
                'amount' => $userItemInfo->amount,
                'name' => $userItemInfo->itemName,
                'description' => $userItemInfo->description,
                'itemType' => $userItemInfo->itemType,
                'icon' => $userItemInfo->icon,
                'stackLimit' => $userItemInfo->stackLimit,
                'userType' => $userItemInfo->useType,
                'source' => $userItemInfo->source,
            ];
        }
        
        $result = new ResultData(ErrorCode::Success);
        $result->items = $items;
        return $result;
    }
}
