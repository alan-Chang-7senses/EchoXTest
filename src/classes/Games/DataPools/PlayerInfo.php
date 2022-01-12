<?php

namespace Games\DataPools;

use Games\Accessors\PlayerAccessor;
use Games\Accessors\SkillAccessor;
use Games\Consts\DNASun;
use Games\Consts\Keys;
use Games\Consts\NFTDNA;
use Games\Consts\SyncRate;
use Games\Players\Adaptability\DurableAdaptability;
use Games\Players\Adaptability\EnvironmentAdaptability;
use Games\Players\Adaptability\TerrainAdaptability;
use Games\Players\Adaptability\WeatherAdaptability;
use Games\Players\Holders\PlayerInfoHolder;
use Games\Players\Holders\PlayerSkillEffectHolder;
use Games\Players\Holders\PlayerSkillHolder;
use Games\Players\Holders\PlayerSkillMaxEffectHolder;
use Games\Players\PlayerAbility;
use Games\Skills\SkillGenerator;
use stdClass;
/**
 * 透過角色ID做為 property 可直接對角色相關資料進行存取
 * 資料將暫存於 memcached 中
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class PlayerInfo extends BasePool {
    
    public static PlayerInfo $instance;
    
    public static function Instance() : PlayerInfo{
        if(empty(self::$instance)) self::$instance = new PlayerInfo();
        return self::$instance;
    }
    
    protected string $keyPrefix = Keys::PlayerPrefix;

    public function FromDB(int|string $playerID) : stdClass|false{
        
        $playerAccessor = new PlayerAccessor();
        $player = $playerAccessor->rowPlayerJoinHolderLevelByPlayerID($playerID);
        if(empty($player)) return false;
        
        $holder = new PlayerInfoHolder();
        $holder->id = $playerID;
        $holder->name = $player->Nickname ?? (string)$playerID;
        $holder->ele = $player->Attribute;
        $holder->sync = $player->SyncRate / SyncRate::Divisor;
        $holder->level = $player->Level;
        $holder->exp = $player->Exp;
        $holder->maxExp = null; //還沒有資料
        $holder->rank = $player->Rank;
        $holder->velocity = PlayerAbility::Velocity($player->Agility, $player->Strength, $player->Level);
        $holder->stamina = PlayerAbility::Stamina($player->Constitution, $player->Dexterity, $player->Level);
        $holder->intelligent = PlayerAbility::Intelligent($player->Dexterity, $player->Agility, $player->Level);
        $holder->breakOut = PlayerAbility::BreakOut($player->Strength, $player->Dexterity, $player->Level);
        $holder->will = PlayerAbility::Will($player->Constitution, $player->Strength, $player->Level);
        
        $DNAs = [$player->HeadDNA, $player->BodyDNA, $player->HandDNA, $player->LegDNA, $player->BackDNA, $player->HatDNA];
        
        $adaptability = new EnvironmentAdaptability();
        PlayerAbility::Adaptability($DNAs, $adaptability, [NFTDNA::DominantOffset, NFTDNA::RecessiveOneOffset], NFTDNA::AttrAdaptOffset, NFTDNA::AttrAdaptLength);
        $holder->dune = $adaptability->dune;
        $holder->volcano = $adaptability->volcano;
        $holder->craterLake = $adaptability->craterLake;
        
        $adaptability = new WeatherAdaptability();
        PlayerAbility::Adaptability($DNAs, $adaptability, [NFTDNA::DominantOffset, NFTDNA::RecessiveTwoOffset], NFTDNA::AttrAdaptOffset, NFTDNA::AttrAdaptLength);
        $holder->sunny = $adaptability->sunny;
        $holder->aurora = $adaptability->aurora;
        $holder->sandDust = $adaptability->sandDust;
        
        $adaptability = new TerrainAdaptability();
        PlayerAbility::Adaptability($DNAs, $adaptability, [NFTDNA::DominantOffset, NFTDNA::RecessiveOneOffset], NFTDNA::SpeciesAdaptOffset, NFTDNA::SpeciesAdaptLength);
        $holder->flat = $adaptability->flat;
        $holder->upslope = $adaptability->upslope;
        $holder->downslope = $adaptability->downslope;
        
        $holder->sun = DNASun::AttrAdapt[$player->Attribute];
        
        $holder->habit = PlayerAbility::Habit($player->Constitution, $player->Strength, $player->Dexterity, $player->Agility);
        
        $adaptability = new DurableAdaptability();
        PlayerAbility::Adaptability($DNAs, $adaptability, [NFTDNA::DominantOffset, NFTDNA::RecessiveTwoOffset], NFTDNA::SpeciesAdaptOffset, NFTDNA::SpeciesAdaptLength);
        $holder->mid = $adaptability->mid;
        $holder->long = $adaptability->long;
        $holder->short = $adaptability->short;
        
        $skillAccessor = new SkillAccessor();
        $aliasCodes = SkillGenerator::aliasCodesByNFT($player);
        $skillInfo = $skillAccessor->rowsInfoByAliasCodes($aliasCodes);
        $skillIDs = [];
        $effectIDs = [];
        $maxEffectIDs = [];
        foreach ($skillInfo as $info){
            $skillIDs[] = $info->SkillID;
            $effectIDs = array_merge($effectIDs, explode(',', $info->Effect));
            $maxEffectIDs = array_merge($maxEffectIDs, explode(',', $info->MaxEffect));
        }
        
        $rows = $playerAccessor->rowsSkillByPlayerIDAndSkillIDs($playerID, $skillIDs);
        $playerSkills = [];
        foreach($rows as $row) $playerSkills[$row->SkillID] = $row;
        
        $rows = $skillAccessor->rowsEffectByEffectIDs($effectIDs);
        $skillEffects = [];
        foreach($rows as $row) $skillEffects[$row->SkillEffectID] = $row;
        
        $rows = $skillAccessor->rowsMaxEffectByEffectIDs($maxEffectIDs);
        $maxSkillEffects = [];
        foreach ($rows as $row) $maxSkillEffects[$row->MaxEffectID] = $row;
        
        $holder->skills = [];
        $skillSlot = [];
        foreach($skillInfo as $info){
            
            $skill = new PlayerSkillHolder();
            $skill->id = $info->SkillID;
            $skill->name = $info->SkillName;
            $skill->type = $info->TriggerType;
            $skill->level = $playerSkills[$info->SkillID]->Level;
            $skill->ranks = [$info->Level1, $info->Level2, $info->Level3, $info->Level4, $info->Level5];
            
            foreach(explode(',', $info->Effect) as $effectID){
                $skill->effects[] = new PlayerSkillEffectHolder(
                    $skillEffects[$effectID]->EffectType,
//                    SkillGenerator::valueByFormuleAndLevelN($skillEffects[$effectID]->Formula, $skill->ranks[$skill->level])
                    $skillEffects[$effectID]->Formula
                );
            }
            
            foreach (explode(',', $info->MaxEffect) as $maxEffectID) {
                $skill->maxEffects[] = new PlayerSkillMaxEffectHolder(
                    $maxSkillEffects[$maxEffectID]->EffectType,
                    $maxSkillEffects[$maxEffectID]->TypeValue,
//                    SkillGenerator::valueByFormuleAndLevelN($maxSkillEffects[$maxEffectID]->Formula, $skill->ranks[$skill->level])
                    $maxSkillEffects[$maxEffectID]->Formula
                );
            }
            
            $holder->skills[] = $skill;
            
            $skillSlot[$playerSkills[$info->SkillID]->Slot] = $info->SkillID;
        }
        
        $holder->skillHole = [];
        for($i = 1; $i <= $player->SlotNumber; ++$i){
            $holder->skillHole[] = $skillSlot[$i] ?? 0;
        }
        
        return $holder;
    }
}
