<?php

namespace Games\DataPools;

use Games\Accessors\PlayerAccessor;
use Games\Consts\DNASun;
use Games\Consts\NFTDNA;
use Games\Consts\SyncRate;
use Games\Players\Adaptability\DurableAdaptability;
use Games\Players\Adaptability\EnvironmentAdaptability;
use Games\Players\Adaptability\TerrainAdaptability;
use Games\Players\Adaptability\WeatherAdaptability;
use Games\Players\Adaptability\WindAdaptability;
use Games\Players\Holders\PlayerDnaHolder;
use Games\Players\Holders\PlayerInfoHolder;
use Games\Players\Holders\PlayerSkillHolder;
use Games\Players\PlayerAbility;
use Generators\DataGenerator;
use stdClass;
/**
 * 透過角色ID做為 property 可直接對角色相關資料進行存取
 * 資料將暫存於 memcached 中
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class PlayerPool extends BasePool {
    
    public static PlayerPool $instance;
    
    public static function Instance() : PlayerPool{
        if(empty(self::$instance)) self::$instance = new PlayerPool();
        return self::$instance;
    }
    
    protected string $keyPrefix = 'player_';

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
        $holder->hp = $holder->stamina;
        $holder->intelligent = PlayerAbility::Intelligent($player->Dexterity, $player->Agility, $player->Level);
        $holder->breakOut = PlayerAbility::BreakOut($player->Strength, $player->Dexterity, $player->Level);
        $holder->will = PlayerAbility::Will($player->Constitution, $player->Strength, $player->Level);
        
        $holder->dna = new PlayerDnaHolder();
        $holder->dna->head = $player->HeadDNA;
        $holder->dna->body = $player->BodyDNA;
        $holder->dna->hand = $player->HandDNA;
        $holder->dna->leg = $player->LegDNA;
        $holder->dna->back = $player->BackDNA;
        $holder->dna->hat = $player->HatDNA;
        
        $adaptability = new EnvironmentAdaptability();
        PlayerAbility::Adaptability($holder->dna, $adaptability, [NFTDNA::DominantOffset, NFTDNA::RecessiveOneOffset], NFTDNA::AttrAdaptOffset, NFTDNA::AttrAdaptLength);
        $holder->dune = $adaptability->dune;
        $holder->craterLake = $adaptability->craterLake;
        $holder->volcano = $adaptability->volcano;
        
        $adaptability = new WindAdaptability();
        PlayerAbility::Adaptability($holder->dna, $adaptability, [NFTDNA::RecessiveOneOffset, NFTDNA::RecessiveTwoOffset], NFTDNA::AttrAdaptOffset, NFTDNA::AttrAdaptLength);
        $holder->tailwind = $adaptability->tailwind;
        $holder->crosswind = $adaptability->crosswind;
        $holder->headwind = $adaptability->headwind;
        
        $adaptability = new WeatherAdaptability();
        PlayerAbility::Adaptability($holder->dna, $adaptability, [NFTDNA::DominantOffset, NFTDNA::RecessiveTwoOffset], NFTDNA::AttrAdaptOffset, NFTDNA::AttrAdaptLength);
        $holder->sunny = $adaptability->sunny;
        $holder->aurora = $adaptability->aurora;
        $holder->sandDust = $adaptability->sandDust;
        
        $adaptability = new TerrainAdaptability();
        PlayerAbility::Adaptability($holder->dna, $adaptability, [NFTDNA::DominantOffset, NFTDNA::RecessiveOneOffset], NFTDNA::SpeciesAdaptOffset, NFTDNA::SpeciesAdaptLength);
        $holder->flat = $adaptability->flat;
        $holder->upslope = $adaptability->upslope;
        $holder->downslope = $adaptability->downslope;
        
        $holder->sun = DNASun::AttrAdapt[$player->Attribute];
        
        $holder->habit = PlayerAbility::Habit($player->Constitution, $player->Strength, $player->Dexterity, $player->Agility);
        
        $adaptability = new DurableAdaptability();
        PlayerAbility::Adaptability($holder->dna, $adaptability, [NFTDNA::DominantOffset, NFTDNA::RecessiveTwoOffset], NFTDNA::SpeciesAdaptOffset, NFTDNA::SpeciesAdaptLength);
        $holder->mid = $adaptability->mid;
        $holder->long = $adaptability->long;
        $holder->short = $adaptability->short;
        
        $rows = $playerAccessor->rowsSkillByPlayerID($playerID);
        $holder->skills = [];
        $slot = [];
        foreach ($rows as $row) {
            $holder->skills[] = new PlayerSkillHolder($row->SkillID, $row->Level, $row->Slot);
            $slot[$row->Slot] = $row->SkillID;
        }
        
        $holder->skillHole = [];
        for($i = 1; $i <= $player->SlotNumber; ++$i){
            $holder->skillHole[] = $slot[$i] ?? 0;
        }
        
        return DataGenerator::ConventType($holder, 'stdClass');
    }
}
