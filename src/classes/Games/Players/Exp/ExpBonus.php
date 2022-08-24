<?php
namespace Games\Players\Exp;

use Games\Random\RandomUtility;

class ExpBonus
{
    /**效果編號 */        
    public int $id;
    /**效果加成 */
    public float $multiplier;
    /**效果優先度，優先度高的會覆蓋低的。值越小優先度越大，無優先度 => null */
    public int|null $priority;
    /**機率。百分比，需介於0~100之間 */
    public float|null $probability;
    private const NoneMultiplier = 1;
    
    public function __construct(int $id, float $multiplier = self::NoneMultiplier,float|null $probability = null, int|null $priority = null)
    {
        $this->id = $id;
        $this->multiplier = $multiplier;
        $this->probability = $probability;
        $this->priority = $priority;
    }

    /**有中回傳效果本身，沒中回null */
    public function GetExpBonus() : ExpBonus|null
    {
        $diceFailed = !empty($this->probability) && !RandomUtility::DicePercentage($this->probability);
        if($diceFailed)return null;                
        return $this;
    }
}