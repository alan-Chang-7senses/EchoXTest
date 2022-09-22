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
            $parts = PlayerUtility::PartCodes($player);
            
            $players[] = [
                'id' => $player->id,
                'idName' => $player->idName,
                'nickname' => $player->name,
                'head' => $parts->head,
                'body' => $parts->body,
                'hand' => $parts->hand,
                'leg' => $parts->leg,
                'back' => $parts->back,
                'hat' => $parts->hat,
            ];
        }

        $result = new ResultData(ErrorCode::Success);
        $result->players = $players;
        return $result;
    }
}
