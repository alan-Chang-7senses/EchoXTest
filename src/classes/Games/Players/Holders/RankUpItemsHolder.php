<?php

namespace Games\Players\Holders;

use stdClass;

class RankUpItemsHolder extends stdClass
{
    public int $rankUpLevel;
    public int $attribute;
    /**[itemID => amount] */
    public array $items;
    public int $coinCost;
}