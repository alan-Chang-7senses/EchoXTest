<?php

namespace Processors\Races;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Games\Consts\PlayerValue;
use Games\Players\PlayerUtility;
use Games\Pools\PlayerPool;
use Helpers\InputHelper;
use Holders\ResultData;
/**
 * Description of RandomBotPlayer
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class RandomBotPlayer extends BaseRace{
    
    protected bool|null $mustInRace = false;

    public function Process(): ResultData {
        
        $amount = InputHelper::post('amount');
        if(empty($amount)) $amount = 1;
        
        $accessor = new PDOAccessor(EnvVar::DBMain);
        $rows = $accessor->FromTable('Users')->WhereLess('UserID', PlayerValue::BotIDLimit)->OrderBy('RAND()')->Limit($amount)->FetchAll();
        
        $playerPool = PlayerPool::Instance();
        
        $players = [];
        foreach ($rows as $row){
            
            $player = $playerPool->{$row->Player};
            
            $players[] = [
                'id' => $player->id,
                'idName' => $player->idName,
                'nickname' => $player->name,
                'head' => PlayerUtility::PartCodeByDNA($player->dna->head),
                'body' => PlayerUtility::PartCodeByDNA($player->dna->body),
                'hand' => PlayerUtility::PartCodeByDNA($player->dna->hand),
                'leg' => PlayerUtility::PartCodeByDNA($player->dna->leg),
                'back' => PlayerUtility::PartCodeByDNA($player->dna->back),
                'hat' => PlayerUtility::PartCodeByDNA($player->dna->hat),
            ];
        }

        $result = new ResultData(ErrorCode::Success);
        $result->players = $players;
        return $result;
    }
}
