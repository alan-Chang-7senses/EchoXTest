<?php
namespace Games\PVP\Holders;

use stdClass;

class CompetitionsInfoHolder extends stdClass
{
    public int $id;
    public int|string $seasonStartTime;
    public int $weeksPerSeason;
    public int $minRatingReset;
    public int $resetRate;
    public int $matchingRange;
    public int $newRoomRate;
    public int $maxMatchSecond;
    public int $extraMatchSecond;
    public int $minMatchPlayers;
    public int $baseRating;
    public int $minRating;
    public int $maxRating;
    public int $score1;
    public int $score2;
    public int $score3;
    public int $score4;
    public int $score5;
    public int $score6;
    public int $score7;
    public int $score8;
    public int $xValue;
    public int $yValue;
    public int $kValue;
    public int $delta;

}