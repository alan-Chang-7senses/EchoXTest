<?php
namespace Games\Players\Exp;

class ExpBonus
{
    /**效果編號 */        
    public int $id;
    /**效果加成 */
    public float $multiplier;
    /**機率。百分比 */
    public float|null $probability;
    public float|int $start;
    private const NoneMultiplier = 1;
    private const BigNumber = 99999;
    
    public function __construct(int $id, float $multiplier = self::NoneMultiplier,float|null $probability = null, float|int $start = 0)
    {
        $this->id = $id;
        $this->multiplier = $multiplier;
        $this->probability = $probability ?? self::BigNumber;
        $this->start = $start;
    }

    /**有中回傳效果本身，沒中回null */
    public function GetExpBonus(int|float $diceNumber) : ExpBonus|null
    {
        $rangeEnd = $this->start + $this->probability;
        $success = $diceNumber > $this->start && $diceNumber <= $rangeEnd;
        return $success ? $this : null;
    }
}