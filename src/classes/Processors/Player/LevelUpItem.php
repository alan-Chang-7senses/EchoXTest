<?php

namespace Processors\Player;

use Consts\ErrorCode;
use Consts\Sessions;
use Games\Users\UserBagHandler;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\BaseProcessor;

class LevelUpItem extends BaseProcessor{
    
    //手續費、同步率、大成功機率
    private const Mode = 
    [
        0 => [0.9, 0,   0],
        1 => [1,   1,   1.2],
        2 => [1.3, 1.5, 1.6]
    ];

    public function Process(): ResultData
    {
        //跟前端要道具名稱跟數量與培養模式
        $itemInfo = json_decode(InputHelper::post('item'));
        $useType = InputHelper::post('type');

        $userID = $_SESSION[Sessions::UserID];
        $ubh = new UserBagHandler($userID);
        // $ubh->GetItemAmount()
        $ubInfo = $ubh->GetItemInfos();
        foreach($itemInfo as $key => $val)
        {
            $validateSuccess = false;
            foreach($ubInfo as $info)
            {
                if($info->itemID == $key && $info->amount >= $val)
                {
                    $validateSuccess = true;
                    break;
                } 
            }
            if(!$validateSuccess) {}//道具未持有或數量不足
        }
        

        $results = new ResultData(ErrorCode::Success);
        $ubInfo = $ubh->GetItemInfos();
        return $results;
    }
}