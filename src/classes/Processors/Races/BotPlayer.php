<?php

namespace Processors\Races;

use Consts\ErrorCode;
use Games\Accessors\UserAccessor;
use Games\Consts\RaceValue;
use Games\Players\PlayerHandler;
use Games\Players\PlayerUtility;
use Games\Users\UserHandler;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\Races\BaseRace;
/**
 * Description of botPlayer
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
abstract class BotPlayer extends BaseRace{
    
    protected bool|null $mustInRace = false;

    public function Process(): ResultData {
        
        $amount = InputHelper::post('amount');
        if(empty($amount)) $amount = 1;
        
        $rows = (new UserAccessor())->rowsByIdleBotAssoc($amount);
        $playerIDs = array_column($rows, 'Player');
        $userIDs = array_column($rows, 'UserID');
        
        $players = [];
        foreach($playerIDs as $id){
            
            $hanlder = new PlayerHandler($id);
            $info = $hanlder->GetInfo();
            
            $players[] = [
                'id' => $info->id,
                'name' => $info->name,
                'head' => PlayerUtility::PartCodeByDNA($info->dna->head),
                'body' => PlayerUtility::PartCodeByDNA($info->dna->body),
                'hand' => PlayerUtility::PartCodeByDNA($info->dna->hand),
                'leg' => PlayerUtility::PartCodeByDNA($info->dna->leg),
                'back' => PlayerUtility::PartCodeByDNA($info->dna->back),
                'hat' => PlayerUtility::PartCodeByDNA($info->dna->hat),
            ];
        }
        
        foreach($userIDs as $id){
            
            $handler = new UserHandler($id);
            $handler->SaveData(['race' => RaceValue::BotMatch]);
        }
        
        $result = new ResultData(ErrorCode::Success);
        $result->players = $players;
        return $result;
    }

}
