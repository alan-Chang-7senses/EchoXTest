<?php

namespace Processors\MainMenu;

use Consts\ErrorCode;
use Consts\Sessions;
use Games\Exceptions\PlayerException;
use Games\Players\PlayerUtility;
use Games\Pools\PlayerPool;
use Games\Pools\ScenePool;
use Games\Pools\UserPool;
use Games\Scenes\SceneUtility;
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
        
        $user = UserPool::Instance()->{$_SESSION[Sessions::UserID]};
        
        $playerInfo = PlayerPool::Instance()->{$user->player};
        
        $player = new stdClass();
        $player->id = $playerInfo->id;
        $player->head = PlayerUtility::PartCodeByDNA($playerInfo->dna->head);
        $player->body = PlayerUtility::PartCodeByDNA($playerInfo->dna->body);
        $player->hand = PlayerUtility::PartCodeByDNA($playerInfo->dna->hand);
        $player->leg = PlayerUtility::PartCodeByDNA($playerInfo->dna->leg);
        $player->back = PlayerUtility::PartCodeByDNA($playerInfo->dna->back);
        $player->hat = PlayerUtility::PartCodeByDNA($playerInfo->dna->hat);
        
        $scene = ScenePool::Instance()->{$user->scene};
        $map = SceneUtility::CurrentClimate($scene->climates);
        unset($map->id);
        unset($map->startTime);
        $map->sceneEnv = $scene->env;
        
        $result = new ResultData(ErrorCode::Success);
        $result->name = $user->nickname;
        $result->money = $user->money;
        $result->energy = $user->vitality;
        $result->roomMax = (int)ConfigGenerator::Instance()->AmountRoomPeopleMax;
        $result->map = $map;
        $result->player = $player;
        
        return $result;
    }
}
