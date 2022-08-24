<?php
namespace Games\Players\Exp;

use Games\Random\RandomUtility;
use stdClass;

class ExpCalculator
{
    private int $rawExp;
    private array $bonus;
    private array $randomBonus;    
    
    private const NoneMultiplier = 1;
    private const MinPriority = 999;

    public function __construct(int $rawExp)
    {
        $this->rawExp = $rawExp;
    }

    /**
     * @param int $id 效果編號
     * @param float $multiplier 加倍量
     * @param float $percentage 機率
     * @param int $priority 優先度(越小越優先)*/
    public function AddRandomBonus(int $id ,float $multiplier, float $percentage, int $priority)
    {
        $this->randomBonus[] = ['id' => $id, 'multiplier' => $multiplier, 'percentage' => $percentage, 'priority' => $priority];
    }

    public function AddBonus(int $id, float $multiplier)
    {
        $this->bonus[] = ['id' => $id, 'multiplier' => $multiplier];
    }    

    public function Process() : stdClass
    {
        $multiplier = self::NoneMultiplier;
        $successBonus = [];

        if(!empty($this->bonus))
        {
            foreach($this->bonus as $b)
            {
                $successBonus[] = $b;
            }
        }
        if(!empty($this->randomBonus))
        {
            $tempMultiplier[] = self::NoneMultiplier;
            $tempSuccessBonus = [];
            $tempPriority = self::MinPriority;            
            foreach($this->randomBonus as $rb)
            {
                if(!RandomUtility::DicePercentage($rb['percentage']))continue;
                $tempSuccessBonus = $rb;
                if($rb['priority'] < $tempPriority)
                {
                    $tempPriority = $rb['priority'];

                }
            }
            foreach($tempSuccessBonus as $bonus)
            {
                if($bonus['priority'] == $tempPriority)$successBonus[] = $bonus;
            }
        }        
        $result = new stdClass();
        if(empty($successBonus)){$result->exp = $this->rawExp;return $result;}        
        foreach($successBonus as $bonus) $multiplier *= $bonus['multiplier'];
        $result->exp = $this->rawExp * $multiplier;
        $result->bonus = $successBonus;        
        return $result;
    }    
}