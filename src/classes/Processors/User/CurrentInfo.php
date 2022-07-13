<?php

namespace Processors\User;

use Consts\ErrorCode;
use Consts\Sessions;
use Games\Users\UserHandler;
use Holders\ResultData;
use Processors\BaseProcessor;
/**
 * Description of CurrentInfo
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class CurrentInfo extends BaseProcessor{
    
    public function Process(): ResultData {
        
        $userHandler = new UserHandler($_SESSION[Sessions::UserID]);
        $userInfo = $userHandler->GetInfo();
        
        $result = new ResultData(ErrorCode::Success);
        $result->info = [
            'userID' => $userInfo->id,
            'nickname' => $userInfo->nickname,
            'level' => $userInfo->level,
            'exp' => $userInfo->exp,
            'petaToken' => $userInfo->petaToken,
            'coin' => $userInfo->coin,
            'power' => $userInfo->power,
            'diamond' => $userInfo->diamond,
            'player' => $userInfo->player,
            'scene' => $userInfo->scene,
            'race' => $userInfo->race,
        ];
        
        return $result;
    }
}
