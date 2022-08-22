<?php

namespace Games\Pools;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Games\Accessors\SkillAccessor;
use Games\Consts\AbilityFactor;
use Games\Consts\DNASun;
use Games\Consts\FreePlayerValue;
use Games\Consts\NFTDNA;
use Games\Consts\PlayerValue;
use Games\Consts\SkillValue;
use Games\Players\Adaptability\EnvironmentAdaptability;
use Games\Players\Adaptability\TerrainAdaptability;
use Games\Players\Adaptability\WeatherAdaptability;
use Games\Players\Adaptability\WindAdaptability;
use Games\Players\Holders\PlayerDnaHolder;
use Games\Players\Holders\PlayerInfoHolder;
use Games\Players\Holders\PlayerSkillHolder;
use Games\Players\PlayerAbility;
use Games\Players\PlayerBaseInfoHolder;
use Games\Players\PlayerUtility;
use Games\Users\FreePeta\FreePetaUtility;
use stdClass;

class FreePlayerPool extends PlayerPool
{
    private static FreePlayerPool $instance;
    public static function Instance(): FreePlayerPool
    {
        if (empty(self::$instance)) self::$instance = new FreePlayerPool();
        return self::$instance;
    }

    protected string $keyPrefix = 'freePlayer_';

    public function FromDB(int|string $number): stdClass|false
    {
        $pdo = new PDOAccessor(EnvVar::DBStatic);
        $table = $pdo->FromTable("FreePetaInfo")->FetchAll();
        $freePlayers = array();
        foreach ($table as $row) {
            if ($row->Type == $number - 1) 
                $freePlayers[] = $row;
        }
        $freePlayerBase = FreePetaUtility::GetRandomElementInArray($freePlayers);
        $pdo->ClearAll();
        $table = $pdo->FromTable("FreePetaDNA")->FetchAll();

        $pdo->ClearAll();
        $skillPartTable = $pdo->FromTable("SkillPart")
            ->FetchAll();
        $holder = new PlayerInfoHolder();
        $holder->freePlayerBase = $freePlayerBase;

        $aliasCodes = [];
        $holder->dna = new PlayerDnaHolder();
        $holder->dna->head = FreePetaUtility::GetRandomElementInArray($table)->HeadDNA;
        $holder->dna->body = FreePetaUtility::GetRandomElementInArray($table)->BodyDNA;
        $holder->dna->hand = FreePetaUtility::GetRandomElementInArray($table)->HandDNA;
        $holder->dna->leg = FreePetaUtility::GetRandomElementInArray($table)->LegDNA;
        $holder->dna->back = FreePetaUtility::GetRandomElementInArray($table)->BackDNA;
        $holder->dna->hat = FreePetaUtility::GetRandomElementInArray($table)->HatDNA;

        $holder->sync = FreePlayerValue::FreePlayerSync; 
        $holder->rank = FreePlayerValue::FreePlayerRank; 

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

        $holder->sun = DNASun::AttrAdapt[$freePlayerBase->Attribute];

        $holder->habit = PlayerAbility::Habit($freePlayerBase->Constitution, $freePlayerBase->Strength, $freePlayerBase->Dexterity, $freePlayerBase->Agility);

        unset($holder->ID);
        $holder->number = $number;
        $holder->level = FreePlayerValue::FreePlayerStartLevel; 


        $aliasCodes  =
            [
                FreePetaUtility::GetPartSkill(PlayerUtility::PartCodeByDNA($holder->dna->head), PlayerValue::Head, $skillPartTable),
                FreePetaUtility::GetPartSkill(PlayerUtility::PartCodeByDNA($holder->dna->body), PlayerValue::Body, $skillPartTable),
                FreePetaUtility::GetPartSkill(PlayerUtility::PartCodeByDNA($holder->dna->hand), PlayerValue::Hand, $skillPartTable),
                FreePetaUtility::GetPartSkill(PlayerUtility::PartCodeByDNA($holder->dna->leg), PlayerValue::Leg, $skillPartTable),
                FreePetaUtility::GetPartSkill(PlayerUtility::PartCodeByDNA($holder->dna->back), PlayerValue::Back, $skillPartTable),
                FreePetaUtility::GetPartSkill(PlayerUtility::PartCodeByDNA($holder->dna->hat), PlayerValue::Hat, $skillPartTable),
            ];
        $sa = new SkillAccessor();

        $rows = $sa->rowsInfoByAliasCodes($aliasCodes);
        $skillrows = [];
        //排序資料
        foreach ($aliasCodes as $code) {
            foreach ($rows as $row) {
                if ($row->AliasCode == $code) $skillrows[] = $row;
            }
        }
        $holder->type = $freePlayerBase->Type;
        $holder->ele = $freePlayerBase->Attribute;
        $base = new PlayerBaseInfoHolder($holder->level,NFTDNA::StrengthNormalC,$freePlayerBase->Strength,$freePlayerBase->Agility,$freePlayerBase->Constitution,$freePlayerBase->Dexterity);
        
        $holder->velocity = PlayerAbility::GetAbilityValue(AbilityFactor::Velocity,$base);
        $holder->stamina = PlayerAbility::GetAbilityValue(AbilityFactor::Stamina,$base);
        $holder->intelligent = PlayerAbility::GetAbilityValue(AbilityFactor::Intelligent,$base);
        $holder->breakOut = PlayerAbility::GetAbilityValue(AbilityFactor::BreakOut,$base);
        $holder->will = PlayerAbility::GetAbilityValue(AbilityFactor::Will,$base);
        $holder->habit = PlayerAbility::Habit($freePlayerBase->Constitution, $freePlayerBase->Strength, $freePlayerBase->Dexterity, $freePlayerBase->Agility);

        $holder->skills = [];
        foreach ($skillrows as $row) {
            $holder->skills[] = new PlayerSkillHolder($row->SkillID, SkillValue::LevelMin, SkillValue::NotInSlot);;
        }
        return $holder;
    }
}
