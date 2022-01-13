<?php

namespace Games\Skills\Formula;
/**
 * Description of SkillFormuleFactory
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class FormulaFactory {
    
    const OperandAll = [
        'H','S',//'SPD','POW','FIG','INT','STA','HP','Gv','Cv',
//        'Env','Wind','Climate','Terrain','Sun','Distance','Origin','Fire','Wood','Water'  
    ];
    const OperandLevelN = 'N';
    const OperatorPercent = '%';
    const OperatorPercentValue = '/100';
    const PrefixFormulaClass = 'Games\Skills\Formula';
    
    public int $playerID;
    public string|null $formula = null;
    
    public function __construct(int $playerID, string|null $formula) {
        
        $this->playerID = $playerID;
        $this->formula = $formula;
    }
    
    public function Process() : float|null{
        
        if($this->formula === null) return null;
        
        $matches = [];
        preg_match_all('/'.implode('|', self::OperandAll).'/', $this->formula, $matches);
        $operands = array_values(array_unique($matches[0]));
        
        $values = [self::OperatorPercent => self::OperatorPercentValue, self::OperandLevelN => $this->levelN];
        foreach ($operands as $operand){
            $className = self::PrefixFormulaClass.$operand;
            $values[$operand] = (new $className($this))->Process();
        }
        
        $result = 0;
        eval('$result = '.strtr($this->formula, $values).';');
        return $result;
    }
    
    public static function ProcessNormal(int $playerID, string|null $formula) : float|null{
        
        $factory = new FormulaFactory($playerID, $formula);
        return $factory->Process();
    }
}
