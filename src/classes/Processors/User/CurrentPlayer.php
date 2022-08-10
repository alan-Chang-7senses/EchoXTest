<?php

namespace Processors\User;

use Consts\ErrorCode;
use Consts\Sessions;
use Games\Consts\RaceValue;
use Games\Exceptions\RaceException;
use Games\Exceptions\UserException;
use Games\Players\PlayerHandler;
use Games\Skills\SkillHandler;
use Games\Users\UserHandler;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\BaseProcessor;
/**
 * Description of CurrentPlayer
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class CurrentPlayer extends BaseProcessor{
    
    const ActionSearch = 1;
    const ActionAssign = 2;
    
    public function Process(): ResultData {
        
        $action = InputHelper::post('action');
        
        $userHandler = new UserHandler($_SESSION[Sessions::UserID]);
        $userInfo = $userHandler->GetInfo();
        if($action == self::ActionAssign && $userInfo->race != RaceValue::NotInRace) throw new RaceException(RaceException::UserInRace);

        $playerID = InputHelper::post('player');
        if(!in_array($playerID, $userInfo->players)) throw new UserException(UserException::NotHoldPlayer, ['[player]' => $playerID]);
        
        if($action == self::ActionAssign) $userHandler->SaveData(['player' => $playerID]);
        
        $playerHandler = new PlayerHandler($playerID);
        $playerInfo = $playerHandler->GetInfo();
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
                'effects' => $handler->GetEffects(),
                'maxEffects' => $handler->GetMaxEffects()
            ];
        }
        
        $result = new ResultData(ErrorCode::Success);
        $result->player = $player;
        
        return $result;
    }
}
