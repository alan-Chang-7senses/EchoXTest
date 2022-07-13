<?php

namespace Processors\User;

use Processors\BaseProcessor;
use Holders\ResultData;
use Consts\ErrorCode;
use Helpers\InputHelper;
use Consts\Sessions;
use Games\Users\UserBagHandler;
use Games\Users\UserItemHandler;


class GetItemsValue extends BaseProcessor{


    public function Process(): ResultData {

        $itemsID = InputHelper::post('itemsID');

        $bagHandler = new UserBagHandler($_SESSION[Sessions::UserID]);
        $bagInfo = $bagHandler->GetInfo($_SESSION[Sessions::UserID]);
        
        $items = [];
        foreach($bagInfo->items as $userItemID){
            
            $userItemHandler = new UserItemHandler($userItemID);
            $userItemHandler->GetItemInfo($userItemID);
            $userItemInfo = $userItemHandler->info;
            if($userItemInfo->amount <= 0)
                continue;
            
                if($userItemInfo->itemID == $itemsID)
                {
                    $items[] = [
                    'name' => $userItemInfo->itemName,
                    'description' => $userItemInfo->description,
                    'amount' => $userItemInfo->amount,
                    'stackLimit' => $userItemInfo->stackLimit,
                    'itemType' => $userItemInfo->itemType,
                    'useType' => $userItemInfo->useType,
                    'source' => $userItemInfo->source,
                ];
                }    
            
        }

        $result = new ResultData(ErrorCode::Success);
        $result->items = $items;
        return $result;
    }
}