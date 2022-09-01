<?php
namespace Games\Players\Exp;

use Games\Random\RandomUtility;
use stdClass;

class ExpBonusCalculator
{
    private int|float $exp;
    private array $bonus = [];
    public int|float $start = 0;
    public int|float $end = 100;

    public function __construct(int|float $rawExp)
    {
        $this->exp = $rawExp;
    }

    public function SetRange(int|float $start, int|float $end)
    {
        $this->start = $start;
        $this->end = $end;
    }
    
    public function AddBonus(ExpBonus $bonus)
    {
        $this->bonus[] = $bonus;
    }

    public function Process() : stdClass
    {
        $bonus = [];
        $dice = RandomUtility::RandomFloat($this->start,$this->end);
        foreach($this->bonus as $b)
        {
            $rt = $b->GetExpBonus($dice);
            if($rt != null)$bonus[] = $rt;
        }
        if(!empty($bonus))
        {            
            foreach($bonus as $b)
            {
                $this->exp *= $b->multiplier;                                
            }
        }
        $result = new stdClass();
        $result->exp = $this->exp;
        $result->bonus = $bonus;
        return $result;
    }
       
}