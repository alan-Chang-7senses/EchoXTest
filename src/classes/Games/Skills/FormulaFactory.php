<?php

namespace Games\Skills;
/**
 * Description of SkillFormuleFactory
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class FormulaFactory {
    
    const OperandAll = [
        'H','S','SPD','POW','FIG','INT','STA','HP','Gv','Cv',
        'Env','Wind','Climate','Terrain','Sun','Distance','Origin','Fire','Wood','Water'  
    ];
    const OperandLevelN = 'N';
    const OperatorPercent = '%';
    const OperatorPercentValue = '/100';
    const PrefixFormulaClass = 'Games\Skills\Formula';
    
    private int $levelN = 1;
    private string $formula = '';
    
    public function __construct(int $levelN, string $formula) {
        
        $this->levelN = $levelN;
        $this->formula = $formula;
    }
    
    public function Process() : float{
        
        $matches = [];
        preg_match_all('/'.implode('|', self::OperandAll).'/', $this->formula, $matches);
        $operands = array_values(array_unique($matches[0]));
        
        $values = [self::OperatorPercent => self::OperatorPercentValue, self::OperandLevelN => $this->levelN];
        foreach ($operands as $operand){
            $className = self::PrefixFormulaClass.$operand;
            $values[$operand] = (new $className($this->levelN))->Process();
        }
        
        $result = 0;
        eval('$result = '.strtr($this->formula, $values).';');
        return $result;
    }
}
