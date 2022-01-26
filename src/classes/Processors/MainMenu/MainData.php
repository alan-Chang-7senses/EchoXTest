<?php

namespace Processors\MainMenu;

use Consts\ErrorCode;
use Consts\Sessions;
use Games\DataPools\PlayerPool;
use Games\DataPools\ScenePool;
use Games\DataPools\UserPool;
use Games\Exceptions\PlayerException;
use Games\Players\PlayerUtility;
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
        
        $configs = ConfigGenerator::Instance();
        
        $playerID = filter_input(INPUT_POST, 'characterID');
        
        $userPool = UserPool::Instance();
        $user = $userPool->{$_SESSION[Sessions::UserID]};
        
        $playerPool = PlayerPool::Instance();
        $playerInfo = false;
        if($playerID !== null) $playerInfo = $playerPool->$playerID;
        if($playerInfo === false && !empty($user->players[0])) $playerInfo = $playerPool->{$user->players[0]};
        if($playerInfo === false) throw new PlayerException(PlayerException::NotFound);
        
        $player = new stdClass();
        $player->id = $playerInfo->id;
        $player->head = PlayerUtility::PartCodeByDNA($playerInfo->dna->head);
        $player->body = PlayerUtility::PartCodeByDNA($playerInfo->dna->body);
        $player->hand = PlayerUtility::PartCodeByDNA($playerInfo->dna->hand);
        $player->leg = PlayerUtility::PartCodeByDNA($playerInfo->dna->leg);
        $player->back = PlayerUtility::PartCodeByDNA($playerInfo->dna->back);
        $player->hat = PlayerUtility::PartCodeByDNA($playerInfo->dna->hat);
        
        $scenePool = ScenePool::Instance();
        $scene = $scenePool->{$user->scene};
        $map = SceneUtility::CurrentClimate($scene->climates);
        unset($map->id);
        unset($map->startTime);
        $map->sceneEnv = $scene->env;
        
        $result = new ResultData(ErrorCode::Success);
        $result->name = $user->nickname;
        $result->money = $user->money;
        $result->energy = $user->vitality;
        $result->roomMax = (int)$configs->AmountRoomPeopleMax;
        $result->map = $map;
        $result->player = $player;
        
        return $result;
    }
}
