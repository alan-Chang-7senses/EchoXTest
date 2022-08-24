<?php
namespace Games\Players\Exp;

use stdClass;

class ExpBonusCalculator
{
    private int $rawExp;
    private array $bonus;
    private const MinPriority = 999999;

    public function __construct(int $rawExp)
    {
        $this->rawExp = $rawExp;
    }
    
    public function AddBonus(ExpBonus $bonus)
    {
        $this->bonus[] = $bonus;
    }

    public function Process() : stdClass
    {
        $bonus = [];
        $bonusPriority = [];
        foreach($this->bonus as $b)
        {
            $bonusResult = $b->GetExpBonus();
            if($bonusResult != null)
            {
                if($bonusResult->priority === null)
                $bonus[] = $bonusResult;
                else
                $bonusPriority[] = $bonusResult;
            }             
        }
        $bonusTemp = $this->GetBonusPriority($bonusPriority);
        if($bonusTemp != null) $bonus[] = $bonusTemp;
        $result = new stdClass();
        if(!empty($bonus))
        {
            foreach($bonus as $b) $this->rawExp *= $b->multiplier;
            $result->bonus = $bonus;
        }
        $result->exp = $this->rawExp;
        return $result;
    }
    
    
    private function GetBonusPriority(array $bonusPriority) : ExpBonus|null
    {
        if(empty($bonusPriority))return null;
        $priorityTemp = self::MinPriority;
        foreach($bonusPriority as $bonus)
        {
            if($bonus->priority < $priorityTemp)
            {
                $priorityTemp = $bonus->priority;
                $rt = $bonus;
            }
        }
        return $rt;
    }   
}