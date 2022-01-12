<?php

namespace Games\Players\Holders;

/**
 * Description of PlayerInfoHolder
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class PlayerInfoHolder {
    
    public int $id = 0;
    public string $name;
    public int $ele;
    public float $sync;
    public int $level;
    public int $exp;
    public int|null $maxExp; //暫時型別
    public int $rank;
    public float $velocity;
    public float $stamina;
    public float $intelligent;
    public float $breakOut;
    public float $will;
    public int $dune;
    public int $volcano;
    public int $craterLake;
    public int $sunny;
    public int $aurora;
    public int $sandDust;
    public int $flat;
    public int $upslope;
    public int $downslope;
    public int $sun;
    public int $habit;
    public int $mid;
    public int $long;
    public int $short;
    /** @var array PlayerSkillHolder */
    public array $skills;
    public array $skillHole;
}
