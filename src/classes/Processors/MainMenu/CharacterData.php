<?php

namespace Processors\MainMenu;

use Consts\ErrorCode;
use Games\Accessors\PlayerAccessor;
use Games\Accessors\SkillAccessor;
use Games\Characters\PlayerAbility;
use Games\Consts\NFTDNA;
use Games\Consts\SyncRate;
use Games\Generators\SkillGenerator;
use Games\Holders\DurableAdaptability;
use Games\Holders\EnvironmentAdaptability;
use Games\Holders\SunAdaptability;
use Games\Holders\TerrainAdaptability;
use Games\Holders\WeatherAdaptability;
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
        
        $characterID = InputHelper::post('characterID');
        
        $playerAccessor = new PlayerAccessor();
        
        $playerFull = $playerAccessor->rowPlayerJoinHolderByCharacterID($characterID);
        
        $creature = new stdClass();
        $creature->id = $playerFull->CharacterID;
        $creature->name = $playerFull->Nickname ?? $playerFull->CharacterID;
        $creature->ele = $playerFull->Attribute;
        $creature->sync = $playerFull->SyncRate / SyncRate::Divisor;
        $creature->level = $playerFull->Level;
        $creature->exp = $playerFull->Exp;
        $creature->maxExp = null;
        $creature->rank = $playerFull->Rank;
        $creature->velocity = PlayerAbility::Velocity($playerFull->Agility, $playerFull->Strength, $playerFull->Level);
        $creature->stamina = PlayerAbility::Stamina($playerFull->Constitution, $playerFull->Dexterity, $playerFull->Level);
        $creature->intelligent = PlayerAbility::Intelligent($playerFull->Dexterity, $playerFull->Agility, $playerFull->Level);
        $creature->breakOut = PlayerAbility::BreakOut($playerFull->Strength, $playerFull->Dexterity, $playerFull->Level);
        $creature->will = PlayerAbility::Will($playerFull->Constitution, $playerFull->Strength, $playerFull->Level);
        
        $DNAs = [$playerFull->HeadDNA, $playerFull->BodyDNA, $playerFull->HandDNA, $playerFull->LegDNA, $playerFull->BackDNA, $playerFull->HatDNA];
        
        $adaptability = new EnvironmentAdaptability();
        PlayerAbility::Adaptability($DNAs, $adaptability, [NFTDNA::DominantOffset, NFTDNA::RecessiveOneOffset], NFTDNA::AttrAdaptOffset, NFTDNA::AttrAdaptLength);
        $creature->dune = $adaptability->dune;
        $creature->volcano = $adaptability->volcano;
        $creature->craterLake = $adaptability->craterLake;
        
        $adaptability = new WeatherAdaptability();
        PlayerAbility::Adaptability($DNAs, $adaptability, [NFTDNA::DominantOffset, NFTDNA::RecessiveTwoOffset], NFTDNA::AttrAdaptOffset, NFTDNA::AttrAdaptLength);
        $creature->sunny = $adaptability->sunny;
        $creature->aurora = $adaptability->aurora;
        $creature->sandDust = $adaptability->sandDust;
        
        $adaptability = new TerrainAdaptability();
        PlayerAbility::Adaptability($DNAs, $adaptability, [NFTDNA::DominantOffset, NFTDNA::RecessiveOneOffset], NFTDNA::SpeciesAdaptOffset, NFTDNA::SpeciesAdaptLength);
        $creature->flat = $adaptability->flat;
        $creature->upslope = $adaptability->upslope;
        $creature->downslope = $adaptability->downslope;
        
        $adaptability = new SunAdaptability();
        PlayerAbility::Adaptability($DNAs, $adaptability, [NFTDNA::DominantOffset, NFTDNA::RecessiveTwoOffset], NFTDNA::SpeciesAdaptOffset, NFTDNA::SpeciesAdaptLength);
        $creature->sun = $adaptability->Value();
        
        $creature->habit = PlayerAbility::Habit($playerFull->Constitution, $playerFull->Strength, $playerFull->Dexterity, $playerFull->Agility);
        
        $adaptability = new DurableAdaptability();
        PlayerAbility::Adaptability($DNAs, $adaptability, [NFTDNA::RecessiveOneOffset, NFTDNA::RecessiveTwoOffset], NFTDNA::SpeciesAdaptOffset, NFTDNA::SpeciesAdaptLength);
        $creature->mid = $adaptability->mid;
        $creature->long = $adaptability->long;
        $creature->short = $adaptability->short;
        
        $skillAccessor = new SkillAccessor();
        $aliasCodes = SkillGenerator::aliasCodesByNFT($playerFull);
        
        $skillInfo = $skillAccessor->rowsInfoByAliasCodes($aliasCodes);
        $skillIDs = [];
        $effectIDs = [];
        $maxEffectIDs = [];
        foreach ($skillInfo as $info){
            $skillIDs[] = $info->SkillID;
            $effectIDs = array_merge($effectIDs, explode(',', $info->Effect));
            $maxEffectIDs = array_merge($maxEffectIDs, explode(',', $info->MaxEffect));
        }
        
        $rows = $playerAccessor->rowsSkillByCharacterIDAndSkillIDs($characterID, $skillIDs);
        $playerSkills = [];
        foreach($rows as $row) $playerSkills[$row->SkillID] = $row;
        
        $rows = $skillAccessor->rowsEffectByEffectIDs($effectIDs);
        $skillEffects = [];
        foreach($rows as $row) $skillEffects[$row->SkillEffectID] = $row;
        
        $rows = $skillAccessor->rowsMaxEffectByEffectIDs($maxEffectIDs);
        $maxSkillEffects = [];
        foreach ($rows as $row) $maxSkillEffects[$row->MaxEffectID] = $row;
        
        $creature->skills = [];
        $skillSlot = [];
        foreach($skillInfo as $info){
            
            $skill = new stdClass();
            $skill->id = $info->SkillID;
            $skill->name = $info->SkillName;
            $skill->type = $info->TriggerType;
            $skill->level = $playerSkills[$info->SkillID]->Level;
            $skill->ranks = [$info->Level1, $info->Level2, $info->Level3, $info->Level4, $info->Level5];
            
            foreach(explode(',', $info->Effect) as $effectID){
                $skill->effects[] = [
                    'type' => $skillEffects[$effectID]->EffectType,
                    'value' => SkillGenerator::valueByFormuleAndLevelN($skillEffects[$effectID]->Formula, $skill->ranks[$skill->level])
                ];
            }
            
            foreach (explode(',', $info->MaxEffect) as $maxEffectID) {
                $skill->maxEffects[] = [
                    'type' => $maxSkillEffects[$maxEffectID]->EffectType,
                    'typeValue' => $maxSkillEffects[$maxEffectID]->TypeValue,
                    'value' => SkillGenerator::valueByFormuleAndLevelN($maxSkillEffects[$maxEffectID]->Formula, $skill->ranks[$skill->level])
                ];
            }
            
            $creature->skills[] = $skill;
            
            $skillSlot[$playerSkills[$info->SkillID]->Slot] = $info->SkillID;
        }
        
        $creature->skillHole = [];
        for($i = 1; $i <= $playerFull->SlotNumber; ++$i){
            $creature->skillHole[] = $skillSlot[$i] ?? 0;
        }
        
        $result = new ResultData(ErrorCode::Success);
        $result->creature = $creature;
        
        return $result;
    }
}
