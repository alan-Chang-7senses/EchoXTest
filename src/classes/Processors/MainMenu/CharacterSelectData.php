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
use stdClass;
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
            
            $row = $playerPool->$playerID;
            $player = new stdClass();
            $player->id = $row->id;
            $player->head = PlayerUtility::PartCodeByDNA($row->dna->head);
            $player->body = PlayerUtility::PartCodeByDNA($row->dna->body);
            $player->hand = PlayerUtility::PartCodeByDNA($row->dna->hand);
            $player->leg = PlayerUtility::PartCodeByDNA($row->dna->leg);
            $player->back = PlayerUtility::PartCodeByDNA($row->dna->back);
            $player->hat = PlayerUtility::PartCodeByDNA($row->dna->hat);
            $players[] = $player;
            
        }
        
        $result = new ResultData(ErrorCode::Success);
        $result->total = count($user->players);
        $result->players = $players;
        
        return $result;
    }
}
