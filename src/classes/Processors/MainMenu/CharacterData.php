<?php

namespace Processors\MainMenu;

use Consts\ErrorCode;
use Games\DataPools\PlayerPool;
use Games\DataPools\SkillEffectPool;
use Games\DataPools\SkillMaxEffectPool;
use Games\DataPools\SkillPool;
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
            
            $skill = $skillPool->{$skillInfo->skillID};
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
            $maxEffect = new stdClass();
            foreach ($skill->maxEffects as $maxEffectID) {
                $row = $skillMaxEffectPool->{$maxEffectID};
                $maxEffect->type = $row->EffectType;
                $maxEffect->typeValue = $row->TypeValue;
                $maxEffect->value = FormulaFactory::ProcessByPlayerSkillMaxEffect($row->Formula, $player, $skill, $row);
                $maxEffects[] = $maxEffect;
            }
            
            $skill->effects = $effects;
            $skill->maxEffects = $maxEffects;
            
            $skills[] = $skill;
        }
        
        $player->skills = $skills;
        
        $result = new ResultData(ErrorCode::Success);
        $result->creature = $player;
        
        return $result;
    }
}
