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
    
    const OperandAll = [ 'FIG', 'INT', 'POW', 'SPD', 'STA', 'HP', 'H', 'N', 'S'];

    private SkillHandler $skillHoandler;
    private string|null $formula;
    private PlayerHandler|null $playerHandler;
    private RacePlayerHandler| null $racePlayerHandler;

    public function __construct(SkillHandler $skill, string|null $formula, PlayerHandler $player, RacePlayerHandler|null $racePlayer = null) {
        
        $this->skillHoandler = $skill;
        $this->formula = $formula;
        $this->playerHandler = $player;
        $this->racePlayerHandler = $racePlayer;
    }

    public function Process() : float|null{
        
        if($this->formula === null) return null;
        
        $matches = [];
        preg_match_all('/'.implode('|', self::OperandAll).'/', $this->formula, $matches);
        $operands = array_values(array_unique($matches[0]));
        
        $values = ['%' => '/100'];
        foreach ($operands as $operand){
            $method = 'Value'.$operand;
            $values[$operand] = $this->$method();
        }
        
        $result = 0;
        eval('$result = '.strtr($this->formula, $values).';');
        return (float) number_format($result, 3);
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
    
    private function ValueN() : float{
        return $this->skillHoandler->GetInfo()->ranks[$this->playerHandler->GetInfo()->level];
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
