<?php

namespace Games\Skills;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\Sessions;
use Games\Consts\SkillValue;
use Games\Players\Holders\PlayerInfoHolder;
use Games\Players\PlayerHandler;
use Games\Players\PlayerUtility;
use Games\Races\RaceHandler;
use Games\Races\RacePlayerHandler;
use Games\Scenes\SceneHandler;
use Games\Skills\SkillHandler;
use Games\Users\UserHandler;
use stdClass;
/**
 * Description of SkillEffectFormula
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class SkillEffectFormula {
    
    const OperandAll = [
        'CraterLake', 'Crosswind', 'Downslope', 'Headwind', 'SandDust', 'Tailwind', 'Upslope', 'Volcano', 'Aurora', 'Sunny', 'Dune', 'Flat',
        'FIG', 'INT', 'POW', 'SPD', 'STA', 'Sun', 'HP', 'H', 'S', 'Red', 'Yellow', 'Blue', 'Green','Feature', 'Genesis',
    ];

    private SkillHandler $skillHandler;
    private string|null $formula;
    private PlayerHandler|null $playerHandler;
    private PlayerInfoHolder|stdClass $playerInfo;
    private RacePlayerHandler| null $racePlayerHandler;

    public function __construct(SkillHandler $skill, string|null $formula, PlayerHandler $player, RacePlayerHandler|null $racePlayer = null) {
        
        $this->skillHandler = $skill;
        $this->formula = $formula;
        $this->playerHandler = $player;
        $this->playerInfo = $player->GetInfo();
        $this->racePlayerHandler = $racePlayer;
    }

    public function Process() : float{
        
        if($this->formula === null) return 0;
        
        $matches = [];
        preg_match_all('/'.implode('|', self::OperandAll).'/', $this->formula, $matches);
        $operands = array_values(array_unique($matches[0]));
        
        $skillInfo = $this->skillHandler->GetInfo();
        $valueN = $skillInfo->ranks[0];
        foreach($this->playerInfo->skills as $playerSkill){
            if($playerSkill->id == $skillInfo->id){
                $valueN = $skillInfo->ranks[$playerSkill->level - 1];
                break;
            }
        }
        
        $values = ['%' => '/100', 'N' => $valueN];
        foreach ($operands as $operand){
            $method = 'Value'.$operand;
            $values[$operand] = $this->$method();
        }
        
        $result = 0;
        eval('$result = '.strtr($this->formula, $values).';');
        return $result;
    }
    
    private function ValueFIG() : float{ return $this->playerInfo->will; }
    private function ValueINT() : float{ return $this->playerInfo->intelligent; }
    private function ValuePOW() : float{ return $this->playerInfo->breakOut; }
    private function ValueSPD() : float{ return $this->playerInfo->breakOut; }
    private function ValueSTA() : float{ return $this->playerInfo->stamina; }
    
    private function ValueHP() : float{ return $this->racePlayerHandler === null ? $this->playerInfo->stamina : $this->racePlayerHandler->GetInfo()->hp; }
    
    private function ValueH() : float{
        
        if($this->racePlayerHandler === null) return SkillValue::SkillH;
        
        return $this->CreateRaceHandler()->ValueH();
    }
    
    private function ValueS() : float{
        
        if($this->racePlayerHandler === null) return SkillValue::SkillS;
        
        return $this->CreateRaceHandler()->ValueS();
    }
    private function ValueGenesis() : int
    {
        $result = 0;
        return $result;
    }
    private function ValueFeature() : int
    {
        $ele = $this->playerInfo->ele;
        $raceHandler = new RaceHandler($this->racePlayerHandler->GetInfo()->race);
        $raceInfo = $raceHandler->GetInfo();
        $accessor = new PDOAccessor(EnvVar::DBMain);
        $result = 0;
        foreach($raceInfo->racePlayers as $id => $raceId)
        {
            $accessor->ClearCondition();
            $accessor->FromTable('PlayerNFT')
                     ->WhereEqual('PlayerID',$id)
                     ->WhereEqual('Attribute', $ele)
                     ->Fetch();
            if($accessor !== false)$result++;
        }
        return $result;
    }
    private function ValueRed(){return 0;}
    private function ValueYellow(){return 0;}
    private function ValueBlue(){return 0;}
    private function ValueGreen(){return 0;}
    
    private function ValueDune() : float{ return PlayerUtility::AdaptValueByPoint($this->playerInfo->dune); }
    private function ValueCraterLake() : float{ return PlayerUtility::AdaptValueByPoint($this->playerInfo->craterLake); }
    private function ValueVolcano() : float{ return PlayerUtility::AdaptValueByPoint($this->playerInfo->volcano); }

    private function ValueTailwind() : float{ return PlayerUtility::AdaptValueByPoint($this->playerInfo->tailwind); }
    private function ValueHeadwind() : float{ return PlayerUtility::AdaptValueByPoint($this->playerInfo->headwind); }
    private function ValueCrosswind() : float{ return PlayerUtility::AdaptValueByPoint($this->playerInfo->crosswind); }
    
    private function ValueSunny() : float{ return PlayerUtility::AdaptValueByPoint($this->playerInfo->sunny); }
    private function ValueAurora() : float{ return PlayerUtility::AdaptValueByPoint($this->playerInfo->aurora); }
    private function ValueSandDust() : float{ return PlayerUtility::AdaptValueByPoint($this->playerInfo->sandDust); }
    
    private function ValueFloat() : float{ return PlayerUtility::AdaptValueByPoint($this->playerInfo->flat); }
    private function ValueUpslope() : float{ return PlayerUtility::AdaptValueByPoint($this->playerInfo->upslope); }
    private function ValueDownslope() : float{ return PlayerUtility::AdaptValueByPoint($this->playerInfo->downslope); }
    
    private function ValueSun() : float{
        
        $userHandler = new UserHandler($_SESSION[Sessions::UserID]);
        $sceneHandler = new SceneHandler($userHandler->GetInfo()->scene);
        $climate = $sceneHandler->GetClimate();
        
        return PlayerUtility::SunValueByLighting($this->playerInfo->sun, $climate->lighting);
    }
    
    private function CreateRaceHandler() : RaceHandler{
        
        $raceHandler = new RaceHandler($this->racePlayerHandler->GetInfo()->race);
        $raceHandler->SetSecne(new SceneHandler($raceHandler->GetInfo()->scene));
        $raceHandler->SetPlayer($this->playerHandler);
        return $raceHandler;
    }
}
