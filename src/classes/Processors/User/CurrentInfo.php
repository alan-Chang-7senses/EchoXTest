<?php

namespace Processors\User;

use Consts\ErrorCode;
use Consts\Sessions;
use Games\Mails\MailsHandler;
use Games\Users\UserUtility;
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
        
        if(empty($userInfo->player)) $userHandler->SaveData(['player' => $userInfo->players[0]]);

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
            'lobby' => $userInfo->lobby,
            'room' => $userInfo->room,
            'unreadmail' => (new MailsHandler())->GetUnreadMails($userInfo->id),
            'raceCount' => UserUtility::GetUserRaceCount($userInfo->id),
            'tutorial' => $userInfo->tutorial,
        ];
        
        return $result;
    }
}
