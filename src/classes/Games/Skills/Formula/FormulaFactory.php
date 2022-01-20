<?php

namespace Games\Skills\Formula;

use stdClass;
/**
 * Description of SkillFormuleFactory
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class FormulaFactory {
    
    const OperandAll = [
        'H','S','N',//'SPD','POW','FIG','INT','STA','HP','Gv','Cv',
//        'Env','Wind','Climate','Terrain','Sun','Distance','Origin','Fire','Wood','Water'  
    ];
    
    const OperandPercent = '%';
    const OperandPercentValue = '/100';
    const PrefixFormulaClass = 'Games\Skills\Formula\Formula';
    
    public stdClass $player;
    public stdClass $skill;
    public string|null $formula = null;
    
    public function __construct(string|null $formula) {
        
        $this->formula = $formula;
    }
    
    public function Process() : float|null{
        
        if($this->formula === null) return null;
        
        $matches = [];
        preg_match_all('/'.implode('|', self::OperandAll).'/', $this->formula, $matches);
        $operands = array_values(array_unique($matches[0]));
        
        $values = [self::OperandPercent => self::OperandPercentValue];
        foreach ($operands as $operand){
            $className = self::PrefixFormulaClass.$operand;
            $values[$operand] = (new $className($this))->Process();
        }
        
        $result = 0;
        eval('$result = '.strtr($this->formula, $values).';');
        return (float) number_format($result, 3);
    }
    
    public static function ProcessByPlayerAndSkill(string|null $formula, stdClass $player, stdClass $skill) : float|null{
        
        $factory = new FormulaFactory($formula);
        $factory->player = $player;
        $factory->skill = $skill;
        return $factory->Process();
    }
}
