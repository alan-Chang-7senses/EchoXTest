<?php

namespace Games\Skills;

use Games\Consts\SkillValue;
use Games\Players\PlayerHandler;
use Games\Races\RaceHandler;
use Games\Races\RacePlayerHandler;
use Games\Scenes\SceneHandler;
use Games\Skills\SkillHandler;
/**
 * Description of SkillEffectFormula
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class SkillEffectFormula {
    
    const OperandAll = [ 'FIG', 'INT', 'POW', 'SPD', 'STA', 'HP', 'H', 'S'];

    private SkillHandler $skillHandler;
    private string|null $formula;
    private PlayerHandler|null $playerHandler;
    private RacePlayerHandler| null $racePlayerHandler;

    public function __construct(SkillHandler $skill, string|null $formula, PlayerHandler $player, RacePlayerHandler|null $racePlayer = null) {
        
        $this->skillHandler = $skill;
        $this->formula = $formula;
        $this->playerHandler = $player;
        $this->racePlayerHandler = $racePlayer;
    }

    public function Process() : float|null{
        
        if($this->formula === null) return null;
        
        $matches = [];
        preg_match_all('/'.implode('|', self::OperandAll).'/', $this->formula, $matches);
        $operands = array_values(array_unique($matches[0]));
        
        $skillInfo = $this->skillHandler->GetInfo();
        $valueN = $skillInfo->ranks[0];
        foreach($this->playerHandler->GetInfo()->skills as $playerSkill){
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
    
    private function ValueFIG() : float{
        return $this->playerHandler->GetInfo()->will;
    }
    
    private function ValueINT() : float{
        return $this->playerHandler->GetInfo()->intelligent;
    }
    
    private function ValuePOW() : float{
        return $this->playerHandler->GetInfo()->breakOut;
    }
    
    private function ValueSPD() : float{
        return $this->playerHandler->GetInfo()->breakOut;
    }
    
    private function ValueSTA() : float{
        return $this->playerHandler->GetInfo()->stamina;
    }
    
    private function ValueHP() : float{
        return $this->racePlayerHandler === null ? $this->playerHandler->GetInfo()->stamina : $this->racePlayerHandler->GetInfo()->hp;
    }
    
    private function ValueH() : float{
        
        if($this->racePlayerHandler === null) return SkillValue::SkillH;
        
        return $this->CreateRaceHandler()->ValueH();
    }
    
    private function ValueS() : float{
        
        if($this->racePlayerHandler === null) return SkillValue::SkillS;
        
        return $this->CreateRaceHandler()->ValueS();
    }
    
    private function CreateRaceHandler() : RaceHandler{
        
        $raceHandler = new RaceHandler($this->racePlayerHandler->GetInfo()->race);
        $raceHandler->SetSecne(new SceneHandler($raceHandler->GetInfo()->scene));
        $raceHandler->SetPlayer($this->playerHandler);
        return $raceHandler;
    }
}
