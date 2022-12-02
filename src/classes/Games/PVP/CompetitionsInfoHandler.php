<?php

namespace Games\PVP;

use Games\Consts\CompetitionsValue;
use Games\Exceptions\RaceException;
use Games\Pools\CompetitionsInfoPool;
use Games\PVP\Holders\CompetitionsInfoHolder;
use stdClass;

class CompetitionsInfoHandler
{
    private static array $instance;
    private CompetitionsInfoHolder|stdClass|false $info;
    private CompetitionsInfoPool $pool;
    private int $currentRating;
    private array $otherCurrentRatings;



    private function __construct(int $lobby)
    {
        $this->pool = CompetitionsInfoPool::Instance();
        $this->info = $this->pool->$lobby;
        if($this->info == false)throw new RaceException(RaceException::NoSeasonData);
    }

    public static function Instance(int $lobby) : CompetitionsInfoHandler
    {
        if(!isset(self::$instance[$lobby])) self::$instance[$lobby] = new CompetitionsInfoHandler($lobby);
        return self::$instance[$lobby];
    }

    public function GetInfo() : CompetitionsInfoHolder|stdClass
    {
        return $this->info;
    }

    /**取得奪冠率 */
    private function GetWinOdds() : float
    {
        $otherRatingAVG = empty($this->otherCurrentRatings) ? 0 :
         array_sum($this->otherCurrentRatings) / count($this->otherCurrentRatings);
        return 1 / (1 + pow(10,(($otherRatingAVG - $this->currentRating) / $this->info->xValue)));
    }
    /**取得未奪冠率 */
    private function GetNotWinOdds() : float
    {
        return 1 - $this->GetWinOdds();
    }

    /**取得期望贏分 */
    private function GetRatingExpect() : float
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

        return $winOdds * $this->info->score1 + $notWindOdds * $scoreAVG;
    }

    /**取得賽後評分 */
    public function GetRating(int $currentRating,array $otherCurrentRatings, int $rank) : int
    {
        $this->currentRating = $currentRating;
        $this->otherCurrentRatings = $otherCurrentRatings;
        $realRating = $this->info->{'score'.$rank};
        $rating = $currentRating + $this->info->kValue * ($realRating - $this->GetRatingExpect()) + $this->info->delta;
        return floor(min(max($rating,$this->info->minRating),$this->info->maxRating));
    }

    /**
     * 取得賽季重置後的評分
     * @param $oldRating == null時回傳基礎分數
     */
    public function GetResetRating(?int $oldRating = null) : int
    {
        if($oldRating === null) return $this->info->baseRating;
        $rt = $oldRating * ($this->info->resetRate / CompetitionsValue::ResetRateDivisor);
        return max(intval($rt),$this->info->minRatingReset);
    }
}