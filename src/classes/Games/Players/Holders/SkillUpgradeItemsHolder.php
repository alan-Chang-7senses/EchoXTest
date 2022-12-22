<?php

namespace Games\Players\Holders;

use stdClass;

class SkillUpgradeItemsHolder extends stdClass
{
    public int $upgradeLevel;
    public int $specieCode;
    /**[itemID => amount] */
    public array $items;
    public int $coinCost;
}