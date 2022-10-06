<?php

namespace Processors\MainMenu;

use Consts\ErrorCode;
use Consts\Sessions;
use Games\Players\PlayerUtility;
use Games\Pools\PlayerPool;
use Games\Pools\UserPool;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\BaseProcessor;
/**
 * Description of CharacterSelectData
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class CharacterSelectData extends BaseProcessor {
    
    public function Process(): ResultData {
        
        $offset = InputHelper::post('offset');
        $count = InputHelper::post('count');
        
        $user = UserPool::Instance()->{$_SESSION[Sessions::UserID]};
        $playerPool = PlayerPool::Instance();
        $players = [];
        foreach (array_slice($user->players, $offset, $count) as $playerID){
            
            $info = $playerPool->$playerID;
            $parts = PlayerUtility::PartCodes($info);
            $players[] = [
                'id' => $info->id,
                'head' => $parts->head,
                'body' => $parts->body,
                'hand' => $parts->hand,
                'leg' => $parts->leg,
                'back' => $parts->back,
                'hat' => $parts->hat,
                'idName' => $info->idName,
                'name' => $info->name,
                'ele' => $info->ele,
                'sync' => $info->sync,
                'level' => $info->level,
                'exp' => $info->exp,
                'maxExp' => $info->maxExp,
                'rank' => $info->rank,
                'velocity' => $info->velocity,
                'stamina' => $info->stamina,
                'intelligent' => $info->intelligent,
                'breakOut' => $info->breakOut,
                'will' => $info->will,
                'dune' => $info->dune,
                'craterLake' => $info->craterLake,
                'volcano' => $info->volcano,
                'tailwind' => $info->tailwind,
                'crosswind' => $info->crosswind,
                'headwind' => $info->headwind,
                'sunny' => $info->sunny,
                'aurora' => $info->aurora,
                'sandDust' => $info->sandDust,
                'flat' => $info->flat,
                'upslope' => $info->upslope,
                'downslope' => $info->downslope,
                'sun' => $info->sun,
                'habit' => $info->habit,
            ];
        }
        
        $result = new ResultData(ErrorCode::Success);
        $result->total = count($user->players);
        $result->players = $players;
        
        return $result;
    }
}
