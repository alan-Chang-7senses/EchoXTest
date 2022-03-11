<?php

namespace Processors\MainMenu;

use Consts\ErrorCode;
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
        
        $player->dune = PlayerUtility::AdaptValueByPoint($player->dune);
        $player->craterLake = PlayerUtility::AdaptValueByPoint($player->craterLake);
        $player->volcano = PlayerUtility::AdaptValueByPoint($player->volcano);
        $player->tailwind = PlayerUtility::AdaptValueByPoint($player->tailwind);
        $player->crosswind = PlayerUtility::AdaptValueByPoint($player->crosswind);
        $player->headwind = PlayerUtility::AdaptValueByPoint($player->headwind);
        $player->sunny = PlayerUtility::AdaptValueByPoint($player->sunny);
        $player->aurora = PlayerUtility::AdaptValueByPoint($player->aurora);
        $player->sandDust = PlayerUtility::AdaptValueByPoint($player->sandDust);
        $player->flat = PlayerUtility::AdaptValueByPoint($player->flat);
        $player->upslope = PlayerUtility::AdaptValueByPoint($player->upslope);
        $player->downslope = PlayerUtility::AdaptValueByPoint($player->downslope);
        
        $player->mid = PlayerUtility::AdaptValueByPoint($player->mid);
        $player->long = PlayerUtility::AdaptValueByPoint($player->long);
        $player->short = PlayerUtility::AdaptValueByPoint($player->short);
        
        $player->skills = $skills;
        unset($player->dna);
        
        $result = new ResultData(ErrorCode::Success);
        $result->creature = $player;
        
        return $result;
    }
}
