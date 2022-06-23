<?php

namespace Processors\MainMenu;

use Consts\ErrorCode;
use Consts\Sessions;
use Games\Players\PlayerHandler;
use Games\Players\PlayerUtility;
use Games\Scenes\SceneHandler;
use Games\Users\UserHandler;
use Generators\ConfigGenerator;
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
        
        $sceneHandler = new SceneHandler($userInfo->scene);
        $map = $sceneHandler->GetClimate();
        unset($map->id);
        unset($map->startTime);
        $map->sceneEnv = $sceneHandler->GetInfo()->env;
        
        $result = new ResultData(ErrorCode::Success);
        $result->name = $userInfo->nickname;
        $result->ucg = $userInfo->ucg;
        $result->coin = $userInfo->coin;
        $result->power = $userInfo->power;
        $result->diamond = $userInfo->diamond;
        $result->roomMax = (int)ConfigGenerator::Instance()->AmountRacePlayerMax;
        $result->map = $map;
        $result->player = $player;
        
        return $result;
    }
}
