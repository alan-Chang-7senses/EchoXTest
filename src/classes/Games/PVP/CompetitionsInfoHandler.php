<?php

namespace Games\PVP;

use Games\Consts\CompetitionsValue;
use Games\Exceptions\RaceException;
use Games\Pools\CompetitionsInfoPool;
use Games\PVP\Holders\CompetitionsInfoHolder;
use stdClass;

class CompetitionsInfoHandler
{
    private CompetitionsInfoHolder|stdClass $info;
    private CompetitionsInfoPool $pool;
    private int $currentRating;
    private array $otherCurrentRatings;

    public function __construct(int $id)
    {
        $this->pool = CompetitionsInfoPool::Instance();
        $this->info = $this->pool->$id;
        if($this->info === false)throw new RaceException(RaceException::NoSeasonData);
    }
    public function GetInfo() : CompetitionsInfoHolder|stdClass
    {
        return $this->info;
    }

    /**取得奪冠率 */
    private function GetWinOdds() : float
    {
        $otherRatingAVG = array_sum($this->otherCurrentRatings) / count($this->otherCurrentRatings);
        return 1 / (1 + 10 * (($otherRatingAVG - $this->currentRating) / $this->info->xValue));
    }
    /**取得未奪冠率 */
    private function GetNotWinOdds() : float
    {
        return 1 - $this->GetWinOdds();
    }

    /**取得期望贏分 */
    private function GetRatingExpect() : int
    {
        $winOdds = $this->GetWinOdds();
        $notWindOdds = $this->GetNotWinOdds();

        $scoreSum = 0;
        $scoreCount = 0;
        for($i = 2; $i <= 8; $i++)
        {
            $scoreSum += $this->info->{'score'.$i};
            $scoreCount++;
        }
        $scoreAVG = $scoreSum / $scoreCount;

        $rt = $winOdds * $this->info->score1 + $notWindOdds * $scoreAVG;
        return intval($rt);
    }

    /**取得賽後評分 */
    public function GetRating(int $currentRating,array $otherCurrentRatings, int $rank) : int
    {
        $this->currentRating = $currentRating;
        $this->otherCurrentRatings = $otherCurrentRatings;
        $realRating = $this->info->{'score'.$rank};
        $rating = $currentRating + $this->info->kValue * ($realRating - $this->GetRatingExpect()) + $this->info->delta;
        return min(max($rating,$this->info->minRating),$this->info->maxRating);
    }

    /**取得賽季重置後的評分 */
    public function GetResetRating(?int $oldRating) : int
    {
        if($oldRating === null) return $this->info->baseRating;
        $rt = $oldRating * ($this->info->resetRate / CompetitionsValue::ResetRateDivisor);
        return min(intval($rt),$this->info->minRatingReset);
    }
}