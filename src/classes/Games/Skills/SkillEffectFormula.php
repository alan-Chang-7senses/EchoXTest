<?php

namespace Games\Skills;

use Games\Players\PlayerHandler;
use Consts\Sessions;
use Games\Races\RacePlayerHandler;
use Games\Users\UserHandler;
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
        return 0;
    }
    
    private function ValuePOW() : float{
        return 0;
    }
    
    private function ValueSPD() : float{
        return 0;
    }
    
    private function ValueSTA() : float{
        return 0;
    }
    
    private function ValueHP() : float{
        return 0;
    }
    
    private function ValueH() : float{
        return 0;
    }
    
    private function ValueN() : float{
        return 0;
    }
    
    private function ValueS() : float{
        return 0;
    }
}
