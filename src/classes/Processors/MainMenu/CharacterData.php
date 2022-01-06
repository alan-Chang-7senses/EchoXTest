<?php

namespace Processors\MainMenu;

use Consts\ErrorCode;
use Games\Accessors\PlayerAccessor;
use Games\Accessors\SkillAccessor;
use Games\Characters\PlayerAbility;
use Games\Consts\NFTDNA;
use Games\Consts\SkillTriggerType;
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
        foreach ($skillInfo as $info){
            $skillIDs[] = $info->SkillID;
            $effectIDs = array_merge($effectIDs, explode(',', $info->Effect));
        }
        
        $rows = $playerAccessor->rowsSkillByCharacterIDAndSkillIDs($characterID, $skillIDs);
        $playerSkills = [];
        foreach($rows as $row) $playerSkills[$row->SkillID] = $row;
        
        $rows = $skillAccessor->rowsEffectByEffectID($effectIDs);
        $skillEffects = [];
        foreach($rows as $row) $skillEffects[$row->SkillEffectID] = $row;
        
        $creature->talent = [];
        $creature->skillInEquip = [];
        $skillSlot = [];
        foreach($skillInfo as $info){
            
            $talent = new stdClass();
            $talent->id = $info->SkillID;
            $talent->name = $info->SkillName;
            $talent->level = $playerSkills[$info->SkillID]->Level;
            $creature->talent[] = $talent;
            
            if($info->TriggerType == SkillTriggerType::Active){
                
                $equip = new stdClass();
                $equip->id = $info->SkillID;
                $equip->name = $info->SkillName;
                $equip->level = $playerSkills[$info->SkillID]->Level;
                
                $effects = explode(',', $info->Effect);
                $equip->effectA = $skillEffects[$effects[0]]->EffectType;
                $equip->effectValueA = $skillEffects[$effects[0]]->Formula;
                $equip->effectB = empty($effects[1]) ? null : $skillEffects[$effects[1]]->EffectType;
                $equip->effectValueB = empty($effects[1]) ? null : $skillEffects[$effects[1]]->Formula;
                
                $creature->skillInEquip[] = $equip;
                $skillSlot[$playerSkills[$info->SkillID]->Slot] = $info->SkillID;
            }
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
