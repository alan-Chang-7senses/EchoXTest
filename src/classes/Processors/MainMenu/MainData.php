<?php

namespace Processors\MainMenu;

use Consts\ErrorCode;
use Consts\Sessions;
use Games\Players\PlayerHandler;
use Games\Players\PlayerUtility;
use Games\Users\UserHandler;
use Holders\ResultData;
use Processors\BaseProcessor;
use stdClass;
/**
 * Description of MainData
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class MainData extends BaseProcessor{
    
    public function Process(): ResultData {
        
        $userInfo = (new UserHandler($_SESSION[Sessions::UserID]))->GetInfo();
        
        $playerInfo = (new PlayerHandler($userInfo->player))->GetInfo();
        
        $player = new stdClass();
        $player->id = $playerInfo->id;
        $player->head = PlayerUtility::PartCodeByDNA($playerInfo->dna->head);
        $player->body = PlayerUtility::PartCodeByDNA($playerInfo->dna->body);
        $player->hand = PlayerUtility::PartCodeByDNA($playerInfo->dna->hand);
        $player->leg = PlayerUtility::PartCodeByDNA($playerInfo->dna->leg);
        $player->back = PlayerUtility::PartCodeByDNA($playerInfo->dna->back);
        $player->hat = PlayerUtility::PartCodeByDNA($playerInfo->dna->hat);
        
        $result = new ResultData(ErrorCode::Success);
        $result->name = $userInfo->nickname;
        $result->petaToken = $userInfo->petaToken;
        $result->coin = $userInfo->coin;
        $result->power = $userInfo->power;
        $result->diamond = $userInfo->diamond;
        $result->player = $player;
        
        return $result;
    }
}
