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
    
    /**
     * @param int $id 效果的代號，方便讓外部知道效果有沒有中
     * @param float $multiplier，加成量
     * @param float|null $probability 機率。不填就是必中
     * @param float|int $start 可以決定哪個區間不會命中。0則是都有機會命中
     */
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