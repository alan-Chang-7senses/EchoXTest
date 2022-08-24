<?php
namespace Games\Players\Exp;

use Games\Random\RandomUtility;

class ExpBonus
{        
    public int $id;
    public float $multiplier;
    public int|null $priority;
    public float|null $probability;
    private const NoneMultiplier = 1;
    
    public function __construct(int $id, float $multiplier = self::NoneMultiplier,float|null $probability = null, int|null $priority = null)
    {
        $this->id = $id;
        $this->multiplier = $multiplier;
        $this->probability = $probability;
        $this->priority = $priority;
    }

    public function GetExpBonus() : ExpBonus|null
    {
        $diceFailed = !empty($this->probability) && !RandomUtility::DicePercentage($this->probability);
        if($diceFailed)return null;                
        return $this;
    }
}