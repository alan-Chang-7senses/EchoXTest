<?php

namespace Processors\Races;

use Consts\ErrorCode;
use Games\Exceptions\PlayerException;
use Games\Exceptions\RaceException;
use Games\Players\PlayerHandler;
use Games\Races\RaceHandler;
use Games\Races\RacePlayerHandler;
use Games\Races\RacePlayerSkillHandler;
use Games\Scenes\SceneHandler;
use Games\Skills\SkillEffectFactory;
use Games\Skills\SkillHandler;
use Helpers\InputHelper;
use Holders\ResultData;
/**
 * Description of LaunchSkill
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class LaunchSkill extends BaseRace{
    
    public function Process(): ResultData {
        
        $skillID = InputHelper::post('id');
        
        $playerHandler = new PlayerHandler($this->userInfo->player);
        $playerInfo = $playerHandler->GetInfo();
        if(!$playerHandler->HasSkill($skillID)) throw new PlayerException(PlayerException::NoSuchSkill, ['[player]' => $playerInfo->id, '[skillID]' => $skillID]);
        
        $raceHandler = new RaceHandler($this->userInfo->race);
        $raceInfo = $raceHandler->GetInfo();
        
        $racePlayerID = $raceInfo->racePlayers->{$this->userInfo->player};
        $racePlayerHandler = new RacePlayerHandler($racePlayerID);
        
        $skillHandler = new SkillHandler($skillID);
        $skillInfo = $skillHandler->GetInfo();
        if(!$racePlayerHandler->EnoughEnergy($skillInfo->energy)) throw new RaceException(RaceException::EnergyNotEnough);
        
        (new RacePlayerSkillHandler($racePlayerID))->Add([
            'RacePlayerID' => $racePlayerID,
            'CreateTime' => microtime(true),
            'SkillID' => $skillID,
        ]);
        
        $skillEffectFactor = new SkillEffectFactory($playerHandler, $racePlayerHandler);
        $playerHandler = $skillEffectFactor->Process();
        
        $raceHandler->SetPlayer($playerHandler);
        $raceHandler->SetSecne(new SceneHandler($this->userInfo->scene));
        
        $result = new ResultData(ErrorCode::Success);
        $result->valueS = $raceHandler->ValueS();
        $result->valueH = $raceHandler->ValueH();
        $result->info = $playerInfo;
        
        return $result;
    }
}
