<?php

namespace Processors\Leaderboard;


use Games\Players\PlayerHandler;
use Games\Players\PlayerUtility;

use Games\Skills\SkillHandler;

use Processors\BaseProcessor;
use stdClass;

/**
 * Description of BaseRivalPlayer
 *
 * @author Lin Zheng Fu <sigma@7senses.com>
 */
abstract class BaseRivalPlayer extends BaseProcessor {

    protected function PartInfo(int $playerID): array {

        $playerHandler = new PlayerHandler($playerID);
        $playerInfo = $playerHandler->GetInfo();

        $parts = PlayerUtility::PartCodes($playerInfo);

        $parts = [
            'id' => $playerInfo->id,
            'head' => $parts->head,
            'body' => $parts->body,
            'hand' => $parts->hand,
            'leg' => $parts->leg,
            'back' => $parts->back,
            'hat' => $parts->hat,
        ];

        return $parts;
    }    

    protected function SkillInfo(int $playerID): stdClass {

        $playerHandler = new PlayerHandler($playerID);
        $playerInfo = $playerHandler->GetInfo();

        $player = clone $playerInfo;        
        unset($player->dna);
        unset($player->userID);
        unset($player->itemName);

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

        return $player;
    }
}