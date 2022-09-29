<?php

namespace Processors\PVE\Holders;

use stdClass;

class ChapterInfoHolder extends stdClass
{
    public int $chapterID;
    public string $name;
    public string $icon;
    // public bool $isAvalible;
    public int $medalAmountFirst;
    public int $rewardIDFirst;
    public int $medalAmountSecond;
    public int $rewardIDSecond;
    public int $medalAmountThird;
    public int $rewardIDThrid;
    public array $levels;
}