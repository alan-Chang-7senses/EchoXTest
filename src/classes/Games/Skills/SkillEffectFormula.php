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
        'FIG', 'INT', 'POW', 'SPD', 'STA', 'Sun', 'HP', 'H', 'S', 'Red', 'Yellow', 'Blue', 'Green','Feature', 'Genesis','Featuer','Clear'
    ];

    private SkillHandler $skillHandler;
    private string|null $formula;
    private PlayerHandler|null $playerHandler;
    private PlayerInfoHolder|stdClass $playerInfo;
    private RacePlayerHandler| null $racePlayerHandler;

    private int $level;

    public function __construct(SkillHandler $skill, string|null $formula, PlayerHandler $player, RacePlayerHandler|null $racePlayer = null, int $level = 0) {
        
        $this->skillHandler = $skill;
        $this->formula = $formula;
        $this->playerHandler = $player;
        $this->playerInfo = $player->GetInfo();
        $this->racePlayerHandler = $racePlayer;
        $this->level = $level;
    }
    public function Process() : float{
        
        if($this->formula === null) return 0;
        
        $matches = [];
        preg_match_all('/'.implode('|', self::OperandAll).'/', $this->formula, $matches);
        $operands = array_values(array_unique($matches[0]));
        
        $skillInfo = $this->skillHandler->GetInfo();
        if($this->level == 0){
            $valueN = $skillInfo->ranks[0];
            foreach($this->playerInfo->skills as $playerSkill){
                if($playerSkill->id == $skillInfo->id){
                    $valueN = $skillInfo->ranks[$playerSkill->level - 1];
                    break;
                }
            }
        }else{
            $valueN = $skillInfo->ranks[$this->level - 1];
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
        if($this->racePlayerHandler == null)return 0;
        $raceHandler = new RaceHandler($this->racePlayerHandler->GetInfo()->race);
        $raceInfo = $raceHandler->GetInfo();
        $accessor = new PDOAccessor(EnvVar::DBMain);
        $ids = array_keys((array)$raceInfo->racePlayers);
        $rows = $accessor->FromTable('PlayerNFT')
                 ->WhereIn('PlayerID',$ids)
                 ->FetchAll();
        if($rows === false)return 0; 
        $rt = 0;                
        foreach($rows as $row)                                  
        {
            if($row->Native > 0)$rt++;
        }
        return $rt;
    }
    private function ValueFeatuer() : int
    {
        if($this->racePlayerHandler == null)return 0;
        $ele = $this->playerInfo->ele;
        $raceHandler = new RaceHandler($this->racePlayerHandler->GetInfo()->race);
        $raceInfo = $raceHandler->GetInfo();
        $accessor = new PDOAccessor(EnvVar::DBMain);
        $ids = array_keys((array)$raceInfo->racePlayers);
        $rows = $accessor->FromTable('PlayerNFT')
                 ->WhereIn('PlayerID',$ids)
                 ->WhereEqual('Attribute',$ele)
                 ->FetchAll();                 
        return $rows === false ? 0 : count((array)$rows) - 1;//把自己扣掉，不算自己。                         
    }
    private function ValueFeature() : int{return $this->ValueFeatuer();}
    private function ValueRed() : int{
        if($this->racePlayerHandler == null)return 0;
        $racePlayerInfo = $this->racePlayerHandler->GetInfo();
        return $racePlayerInfo->energy[SkillValue::EnergyRed];
    }private function ValueYellow() : int{
        if($this->racePlayerHandler == null)return 0;
        $racePlayerInfo = $this->racePlayerHandler->GetInfo();
        return $racePlayerInfo->energy[SkillValue::EnergyYellow];
    }private function ValueBlue() : int{
        if($this->racePlayerHandler == null)return 0;
        $racePlayerInfo = $this->racePlayerHandler->GetInfo();
        return $racePlayerInfo->energy[SkillValue::EnergyBlue];
    }private function ValueGreen() : int{
        if($this->racePlayerHandler == null)return 0;
        $racePlayerInfo = $this->racePlayerHandler->GetInfo();
        return $racePlayerInfo->energy[SkillValue::EnergyGreen];
    }

    
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
