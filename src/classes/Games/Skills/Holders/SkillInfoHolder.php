<?php
namespace Games\Skills\Holders;

use stdClass;
/**
 * Description of SkillInfoHolder
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class SkillInfoHolder extends stdClass{
    public int $id;
    public string $name;    
    public string $icon;
    public string $description;
    public float $cooldown;
    public array $energy;
    public float $duration;
    public array $ranks;
    public array $effects;
    public string $maxDescription;
    public int $maxCondition;
    public int $maxConditionValue;
    public string $attackedDesc;
    public array $maxEffects;
}
