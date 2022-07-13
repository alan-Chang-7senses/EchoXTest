<?php

namespace Processors\User;

use Processors\BaseProcessor;
use Holders\ResultData;
use Consts\ErrorCode;
use Helpers\InputHelper;
use Consts\Sessions;
use Games\Users\UserBagHandler;
use Games\Users\UserItemHandler;

class UseItems extends BaseProcessor{
    public function Process(): ResultData {
    
        $userItemsID = InputHelper::post('userItemsID');
        $amount = InputHelper::post('amount');

        $bagHandler = new UserBagHandler($_SESSION[Sessions::UserID]);
        $bagInfo = $bagHandler->GetInfo($_SESSION[Sessions::UserID]);
        
        $items = [];
        foreach($bagInfo->items as $userItemID){
                
            $userItemHandler = new UserItemHandler($userItemID);
            $userItemHandler->DeleteCache();
            $userItemHandler->GetItemInfo($userItemID);

            if($userItemID == null)
            continue;
            if($userItemHandler->info->id == $userItemsID)
            {
                $items = $userItemHandler->UseItem($amount);
            }
        }
    
    
        $result = new ResultData(ErrorCode::Success);
        $result->items = $items;
        return $result;
    
    }


}