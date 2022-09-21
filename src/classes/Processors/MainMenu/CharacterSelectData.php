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
            ];
        }
        
        $result = new ResultData(ErrorCode::Success);
        $result->total = count($user->players);
        $result->players = $players;
        
        return $result;
    }
}
