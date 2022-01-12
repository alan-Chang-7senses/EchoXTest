<?php

namespace Processors\MainMenu;

use Consts\ErrorCode;
use Consts\Sessions;
use Games\Accessors\PlayerAccessor;
use Games\Players\PlayerUtility;
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
        
        $userID = $_SESSION[Sessions::UserID];
        
        $offset = InputHelper::post('offset');
        $count = InputHelper::post('count');
        
        $playerAccessor = new PlayerAccessor();
        $rows = $playerAccessor->rowsPlayerJoinHolderByUserIDLimit($userID, $offset, $count);
        
        $players = [];
        foreach($rows as $row){
            
            $player = new stdClass();
            $player->id = $row->PlayerID;
            $player->head = PlayerUtility::PartCodeByDNA($row->HeadDNA);
            $player->body = PlayerUtility::PartCodeByDNA($row->BodyDNA);
            $player->hand = PlayerUtility::PartCodeByDNA($row->HandDNA);
            $player->leg = PlayerUtility::PartCodeByDNA($row->LegDNA);
            $player->back = PlayerUtility::PartCodeByDNA($row->BackDNA);
            $player->hat = PlayerUtility::PartCodeByDNA($row->HatDNA);
            $players[] = $player;
        }
        
        $result = new ResultData(ErrorCode::Success);
        $result->total = $playerAccessor->countHolderByUserID($userID);
        $result->players = $players;
        
        return $result;
    }
}
