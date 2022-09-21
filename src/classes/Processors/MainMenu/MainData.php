<?php

namespace Processors\MainMenu;

use Consts\ErrorCode;
use Consts\Sessions;
use Games\Mails\MailsHandler;
use Games\Players\PlayerHandler;
use Games\Players\PlayerUtility;
use Games\Users\UserHandler;
use Holders\ResultData;
use Processors\BaseProcessor;

/**
 * Description of MainData
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class MainData extends BaseProcessor{
    
    public function Process(): ResultData {
        
        $userInfo = (new UserHandler($_SESSION[Sessions::UserID]))->GetInfo();
        
        $playerInfo = (new PlayerHandler($userInfo->player))->GetInfo();
        $parts = PlayerUtility::PartCodes($playerInfo);
        
        $result = new ResultData(ErrorCode::Success);
        $result->name = $userInfo->nickname;
        $result->petaToken = $userInfo->petaToken;
        $result->coin = $userInfo->coin;
        $result->power = $userInfo->power;
        $result->diamond = $userInfo->diamond;
        $result->player = [
            'id' => $playerInfo->id,
            'head' => $parts->head,
            'body ' => $parts->body,
            'hand' => $parts->hand,
            'leg' => $parts->leg,
            'back' => $parts->back,
            'hat' => $parts->hat,
        ];

        $userMailsHandler = new MailsHandler();
        $result->unreadmail = $userMailsHandler->GetUnreadMails($_SESSION[Sessions::UserID]);
        
        return $result;
    }
}
