<?php

namespace Processors\MainMenu;

use Accessors\PDOAccessor;
use Consts\ErrorCode;
use Games\Characters\PlayerAbility;
use Games\Consts\NFTDNA;
use Games\Consts\SyncRate;
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
        
        $row = (new PDOAccessor('KoaMain'))
                ->FromTableJoinUsing('CharacterNFT', 'CharacterHolder', 'INNER', 'CharacterID')
                ->FromTableJoinUsingNext('CharacterLevel', 'LEFT', 'CharacterID')
                ->WhereEqual('CharacterID', $characterID)->Limit(1)->Fetch();
        
        $creature = new stdClass();
        $creature->id = $row->CharacterID;
        $creature->name = $row->Nickname ?? $row->CharacterID;
        $creature->ele = $row->Attribute;
        $creature->sync = $row->SyncRate / SyncRate::Divisor;
        $creature->level = $row->Level;
        $creature->exp = $row->Exp;
        $creature->maxExp = null;
        $creature->rank = $row->Rank;
        $creature->velocity = PlayerAbility::Velocity($row->Agility, $row->Strength, $row->Level);
        $creature->stamina = PlayerAbility::Stamina($row->Constitution, $row->Dexterity, $row->Level);
        $creature->intelligent = PlayerAbility::Intelligent($row->Dexterity, $row->Agility, $row->Level);
        $creature->breakOut = PlayerAbility::BreakOut($row->Strength, $row->Dexterity, $row->Level);
        $creature->will = PlayerAbility::Will($row->Constitution, $row->Strength, $row->Level);
        
        $DNAs = [$row->HeadDNA, $row->BodyDNA, $row->HandDNA, $row->LegDNA, $row->BackDNA, $row->HatDNA];
        
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
        PlayerAbility::Adaptability($DNAs, $adaptability,  [NFTDNA::DominantOffset, NFTDNA::RecessiveTwoOffset], NFTDNA::SpeciesAdaptOffset, NFTDNA::SpeciesAdaptLength);
        $creature->sun = $adaptability->Value();
        
        $result = new ResultData(ErrorCode::Success);
        $result->creature = $creature;
        $result->row = $row;
        
        return $result;
    }
}
