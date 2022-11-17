<?php

namespace Games\Pools;

use Accessors\MemcacheAccessor;
use Accessors\PoolAccessor;
use Games\Accessors\PlayerAccessor;
use Games\Consts\AbilityFactor;
use Games\Consts\DNASun;
use Games\Consts\NFTDNA;
use Games\Consts\SkillValue;
use Games\Consts\SyncRate;
//use Games\Players\Adaptability\DurableAdaptability;
use Games\Players\Adaptability\EnvironmentAdaptability;
use Games\Players\Adaptability\SlotNumber;
use Games\Players\Adaptability\TerrainAdaptability;
use Games\Players\Adaptability\WeatherAdaptability;
use Games\Players\Adaptability\WindAdaptability;
use Games\Players\Exp\PlayerEXP;
use Games\Players\Holders\PlayerDnaHolder;
use Games\Players\Holders\PlayerInfoHolder;
use Games\Players\Holders\PlayerSkillHolder;
use Games\Players\PlayerAbility;
use Games\Players\Holders\PlayerBaseInfoHolder;
use Games\Players\PlayerUtility;
use Games\Players\SyncRateUtility;
use stdClass;
/**
 * 透過角色ID做為 property 可直接對角色相關資料進行存取
 * 資料將暫存於 memcached 中
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class PlayerPool extends PoolAccessor {
    
    private static PlayerPool $instance;
    
    public static function Instance() : PlayerPool{
        if(empty(self::$instance)) self::$instance = new PlayerPool();
        return self::$instance;
    }
    
    protected string $keyPrefix = 'player_';

    protected ?int $playerLevelSpecify = null;
    protected ?int $skillLevelSpecify = null;
    
    public function FromDB(int|string $playerID) : stdClass|false{
        
        $playerAccessor = new PlayerAccessor();
        $player = $playerAccessor->rowPlayerJoinHolderLevelByPlayerID($playerID);
        if(empty($player)) return false;
        
        $holder = new PlayerInfoHolder();
        $holder->id = $playerID;
        $holder->idName = PlayerUtility::GetIDName($playerID);
        $holder->name = $player->Nickname ?? $holder->idName;
        $holder->ele = $player->Attribute;
        $holder->sync = $player->SyncRate / SyncRate::Divisor;
        $holder->level = $this->playerLevelSpecify ?? $player->Level;
        $holder->exp = $player->Exp;
        $holder->rank = $player->Rank;
        $holder->maxExp = PlayerEXP::GetNextLevelRequireEXP($holder->level,$holder->rank,$holder->exp);
        $holder->strengthLevel = $player->StrengthLevel;
        $holder->skeletonType = $player->SkeletonType;

        $baseInfo = new PlayerBaseInfoHolder($holder->level,$holder->strengthLevel,$player->Strength,$player->Agility,$player->Constitution,$player->Dexterity);
        $holder->velocity = PlayerAbility::GetAbilityValue(AbilityFactor::Velocity,$baseInfo);
        $holder->stamina = PlayerAbility::GetAbilityValue(AbilityFactor::Stamina,$baseInfo);
        $holder->intelligent = PlayerAbility::GetAbilityValue(AbilityFactor::Intelligent,$baseInfo);
        $holder->breakOut = PlayerAbility::GetAbilityValue(AbilityFactor::BreakOut,$baseInfo);
        $holder->will = PlayerAbility::GetAbilityValue(AbilityFactor::Will,$baseInfo);

        SyncRateUtility::ApplySyncRateBonus($holder,$holder->sync);
        
        $holder->dna = new PlayerDnaHolder();
        $holder->dna->head = $player->HeadDNA;
        $holder->dna->body = $player->BodyDNA;
        $holder->dna->hand = $player->HandDNA;
        $holder->dna->leg = $player->LegDNA;
        $holder->dna->back = $player->BackDNA;
        $holder->dna->hat = $player->HatDNA;
        
        $adaptability = new EnvironmentAdaptability();
        PlayerAbility::Adaptability($holder->dna, $adaptability, [NFTDNA::DominantOffset, NFTDNA::RecessiveOneOffset], NFTDNA::SpeciesAdaptOffset, NFTDNA::SpeciesAdaptLength);
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
        PlayerAbility::Adaptability($holder->dna, $adaptability, [NFTDNA::DominantOffset, NFTDNA::RecessiveOneOffset], NFTDNA::AttrAdaptOffset, NFTDNA::AttrAdaptLength);
        $holder->flat = $adaptability->flat;
        $holder->upslope = $adaptability->upslope;
        $holder->downslope = $adaptability->downslope;
        
        $holder->sun = DNASun::AttrAdapt[$player->Attribute];
        
        $holder->habit = PlayerAbility::Habit($player->Constitution, $player->Strength, $player->Dexterity, $player->Agility);
        
//        $adaptability = new DurableAdaptability();
//        PlayerAbility::Adaptability($holder->dna, $adaptability, [NFTDNA::DominantOffset, NFTDNA::RecessiveTwoOffset], NFTDNA::SpeciesAdaptOffset, NFTDNA::SpeciesAdaptLength);
//        $holder->mid = $adaptability->mid;
//        $holder->long = $adaptability->long;
//        $holder->short = $adaptability->short;
        
        $slotNumber = new SlotNumber();
        PlayerAbility::Adaptability($holder->dna, $slotNumber, [NFTDNA::DominantOffset], NFTDNA::AttrAdaptOffset, NFTDNA::AttrAdaptLength);
        $holder->slotNumber = $slotNumber->Value();
        
        $rows = $playerAccessor->rowsSkillByPlayerID($playerID);
        $holder->skills = [];
        $slot = [];
        foreach ($rows as $row) {
            $level = $this->skillLevelSpecify ?? $row->Level;
            $holder->skills[] = new PlayerSkillHolder($row->SkillID, $level, $row->Slot);
            $slot[$row->Slot] = $row->SkillID;
        }
        
        $holder->skillHole = [];
        for($i = 1; $i <= $holder->slotNumber; ++$i){
            $holder->skillHole[] = $slot[$i] ?? SkillValue::NotInSlot;
        }
        
        $this->AutoPutSlot($holder);
        
        return $holder;
    }
    
    private function AutoPutSlot(PlayerInfoHolder $holder) : void{
        
        if(array_unique($holder->skillHole) != [0]) return;
        
        for($i = 0; $i < $holder->slotNumber; ++$i){
            if(!isset($holder->skills[$i])) return;
            $holder->skillHole[$i] = $holder->skills[$i]->id;
            $holder->skills[$i]->slot = $i + 1;
        }
    }

    //TODO：刪除自身以外，指定等級之快取也一併刪除。
    public function Delete(string $id) : bool{
        $key = $this->keyPrefix.$id;
        $res = MemcacheAccessor::Instance()->delete($key);
        if($res) unset ($this->$id);
        SpecifyPlayerPool::Instance()->Delete($id);
        return $res;
    }
    
    public function DeleteAll(array $ids) : void{
        $memcacheAccessor = MemcacheAccessor::Instance();
        foreach($ids as $id){
            $key = $this->keyPrefix.$id;
            $res = $memcacheAccessor->delete($key);
            if($res) unset ($this->$id);
            SpecifyPlayerPool::Instance()->Delete($id);
        }
    }

}
