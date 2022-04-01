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
    public string $description;
    public float $cooldown;
    public array $energy;
    public array $ranks;
    public array $effects;
    public string $maxDescription;
    public int $maxCondition;
    public int $maxConditionValue;
    public array $maxEffects;
}
