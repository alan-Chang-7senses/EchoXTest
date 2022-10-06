<?php

namespace Processors\MainMenu;

use Consts\ErrorCode;
use Games\Consts\AdaptablilityLevel;
use Games\Players\PlayerUtility;
use Games\Pools\PlayerPool;
use Games\Pools\SkillEffectPool;
use Games\Pools\SkillMaxEffectPool;
use Games\Pools\SkillPool;
use Games\Skills\Formula\FormulaFactory;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\BaseProcessor;
use stdClass;
/**
 * Description of CharacterData
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class CharacterData extends BaseProcessor{
    
    public function Process(): ResultData {
        
        $playerID = InputHelper::post('characterID');
        
        $player = PlayerPool::Instance()->$playerID;
        $skillPool = SkillPool::Instance();
        $skillEffectPool = SkillEffectPool::Instance();
        $skillMaxEffectPool = SkillMaxEffectPool::Instance();
        
        $skills = [];
        foreach($player->skills as $skillInfo){
            
            $skill = $skillPool->{$skillInfo->id};
            $skill->level = $skillInfo->level;
            
            $effects = [];
            $effect = new stdClass();
            foreach($skill->effects as $effectID){
                $row = $skillEffectPool->{$effectID};
                $effect->type = $row->EffectType;
                $effect->value = FormulaFactory::ProcessByPlayerAndSkill($row->Formula, $player, $skill);
                $effects[] = $effect;
            }
            
            $maxEffects = [];
            foreach ($skill->maxEffects as $maxEffectID) {
                $row = $skillMaxEffectPool->{$maxEffectID};
                $maxEffect = new stdClass();
                $maxEffect->type = $row->EffectType;
                $maxEffect->typeValue = $row->TypeValue;
                $maxEffect->value = FormulaFactory::ProcessByPlayerSkillMaxEffect($row->Formula, $player, $skill, $row);
                $maxEffects[] = $maxEffect;
            }
            
            $skill->effects = $effects;
            $skill->maxEffects = $maxEffects;
            
            $skills[] = $skill;
        }
        
        $player->dune = PlayerUtility::AdaptValueByPoint($player->dune,AdaptablilityLevel::Enviroment);
        $player->craterLake = PlayerUtility::AdaptValueByPoint($player->craterLake,AdaptablilityLevel::Enviroment);
        $player->volcano = PlayerUtility::AdaptValueByPoint($player->volcano,AdaptablilityLevel::Enviroment);
        $player->tailwind = PlayerUtility::AdaptValueByPoint($player->tailwind,AdaptablilityLevel::Wind);
        $player->crosswind = PlayerUtility::AdaptValueByPoint($player->crosswind,AdaptablilityLevel::Wind);
        $player->headwind = PlayerUtility::AdaptValueByPoint($player->headwind,AdaptablilityLevel::Wind);
        $player->sunny = PlayerUtility::AdaptValueByPoint($player->sunny,AdaptablilityLevel::Climate);
        $player->aurora = PlayerUtility::AdaptValueByPoint($player->aurora,AdaptablilityLevel::Climate);
        $player->sandDust = PlayerUtility::AdaptValueByPoint($player->sandDust,AdaptablilityLevel::Climate);
        $player->flat = PlayerUtility::AdaptValueByPoint($player->flat,AdaptablilityLevel::Terrian);
        $player->upslope = PlayerUtility::AdaptValueByPoint($player->upslope,AdaptablilityLevel::Terrian);
        $player->downslope = PlayerUtility::AdaptValueByPoint($player->downslope,AdaptablilityLevel::Terrian);
        
        // $player->mid = PlayerUtility::AdaptValueByPoint($player->mid);
        // $player->long = PlayerUtility::AdaptValueByPoint($player->long);
        // $player->short = PlayerUtility::AdaptValueByPoint($player->short);
        
        $player->skills = $skills;
        unset($player->dna);
        
        $result = new ResultData(ErrorCode::Success);
        $result->creature = $player;
        
        return $result;
    }
}
