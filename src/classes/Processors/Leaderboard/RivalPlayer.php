<?php

namespace Processors\Leaderboard;

use Consts\ErrorCode;

use Games\Players\PlayerHandler;
use Games\Players\PlayerUtility;

use Games\Skills\SkillHandler;

use Games\Leadboards\LeadboardUtility;

use Games\PVP\QualifyingHandler;
use Games\Races\RaceUtility;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\BaseProcessor;

/**
 * Description of RivalPlayer
 *
 * @author Lin Zheng Fu <sigma@7senses.com>
 */
class RivalPlayer extends BaseProcessor{

    public function Process(): ResultData {
        
        $playerID = InputHelper::post('player');

        $lobby = InputHelper::post('lobby');

        $playerHandler = new PlayerHandler($playerID);
        $playerInfo = $playerHandler->GetInfo();

        $parts = PlayerUtility::PartCodes($playerInfo);

        $result = new ResultData(ErrorCode::Success);
        $result->parts = [
            'id' => $playerInfo->id,
            'head' => $parts->head,
            'body' => $parts->body,
            'hand' => $parts->hand,
            'leg' => $parts->leg,
            'back' => $parts->back,
            'hat' => $parts->hat,
        ];
        

        $player = clone $playerInfo;        
        unset($player->dna);

        $player->skills = [];
        foreach($playerInfo->skills as $skill){
            
            $handler = new SkillHandler($skill->id);
            $handler->playerHandler = $playerHandler;
            $info = $handler->GetInfo();
            $player->skills[] = [
                'id' => $info->id,
                'name' => $info->name,
                'icon' => $info->icon,
                'description' => $info->description,
                'level' => $skill->level,
                'slot' => $skill->slot,
                'energy' => $info->energy,
                'cooldown' => $info->cooldown,
                'duration' => $info->duration,
                'ranks' => $info->ranks,
                'maxDescription' => $info->maxDescription,
                'maxCondition' => $info->maxCondition,
                'maxConditionValue' => $info->maxConditionValue,
                'attackedDesc' => $info->attackedDesc,
                'effects' => $handler->GetEffects(),
                'maxEffects' => $handler->GetMaxEffects()
            ];
        }

        $result->player = $player;


        $qualifyingHandler = new QualifyingHandler();
        $ranking = LeadboardUtility::PlayerLeadRanking($lobby, $playerID, $qualifyingHandler->GetSeasonIDByLobby($lobby));
        $result->ranking = [
            'ranking' => $ranking->ranking,
            'playCount' => $ranking->playCount,
            'leadRate' => $ranking->leadRate,
        ];
       
        return $result;
    }    
}