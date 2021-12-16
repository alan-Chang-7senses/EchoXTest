<?php

namespace Processors\MainMenu;

use Processors\BaseProcessor;
use Holders\ResultData;
use Helpers\InputHelper;
use Consts\Sessions;
use Games\Characters\Avatar;
use Accessors\PDOAccessor;
use Consts\ErrorCode;
use stdClass;
use Games\Consts\SyncRate;
use Games\Characters\PlayerAbility;
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
        $environmentAdaptability = PlayerAbility::EnvironmentAdaptability($DNAs);
        $creature->dune = $environmentAdaptability->dune;
        $creature->volcano = $environmentAdaptability->volcano;
        $creature->craterLake = $environmentAdaptability->craterLake;
        
        $result = new ResultData(ErrorCode::Success);
        $result->creature = $creature;
//        $result->row = $row;
//        $result->ada1 = substr($row->HeadDNA, 0, 8);
//        $result->ada2 = substr($row->HeadDNA, 8, 8);
//        $result->ada3 = substr($row->HeadDNA, 16, 8);
//        $result->ea = $environmentAdaptability;
        
        return $result;
    }
}
