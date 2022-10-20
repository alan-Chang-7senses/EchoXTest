<?php

namespace Processors\User;

use Consts\ErrorCode;
use Consts\Sessions;
use Games\Consts\PlayerValue;
use Games\Consts\RaceValue;
use Games\Exceptions\RaceException;
use Games\Exceptions\UserException;
use Games\Players\Exp\PlayerEXP;
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
        $playerID = InputHelper::post('player');
        
        $userHandler = new UserHandler($_SESSION[Sessions::UserID]);
        $userInfo = $userHandler->GetInfo();
        if(($action == self::ActionAssign || empty($playerID)) && $userInfo->race != RaceValue::NotInRace) throw new RaceException(RaceException::UserInRace);

        if(empty($playerID)){
            $playerID = $userInfo->players[0];
            $action = self::ActionAssign;
        }
        
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
                'attackedDesc' => $info->attackedDesc,
                'effects' => $handler->GetEffects(),
                'maxEffects' => $handler->GetMaxEffects()
            ];
        }

        $currentExp = $playerInfo->exp;
        $currentMax = PlayerEXP::GetLevelRequireExp($playerInfo->level + PlayerEXP::LevelUnit);
        $currentRequire = PlayerEXP::GetLevelRequireExp($playerInfo->level);
        $currentMax = $playerInfo->level == PlayerValue::LevelMax ? $currentRequire : $currentMax - PlayerEXP::ExpUnit;
        
        $result = new ResultData(ErrorCode::Success);
        $result->player = $player;
        $result->level = $playerInfo->level;
        $result->currentExp = $currentExp;
        $result->currentLevelExpMax = $currentMax;
        $result->currentLevelExpMin = $currentRequire;
        return $result;
    }
}
