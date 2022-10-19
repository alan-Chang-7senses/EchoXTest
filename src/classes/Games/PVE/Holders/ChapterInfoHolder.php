<?php
namespace Games\PVE\Holders;

use stdClass;

class ChapterInfoHolder extends stdClass
{
    public int $chapterID;
    public string $name;
    public string $icon;
    public array|null $preChapters;
    public int $medalAmountFirst;
    public int $rewardIDFirst;
    public int $medalAmountSecond;
    public int $rewardIDSecond;
    public int $medalAmountThird;
    public int $rewardIDThrid;
    public array $levels;
}