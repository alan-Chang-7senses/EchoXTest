<?php
namespace Games\Skills\Holders;
/**
 * Description of SkillInfoHolder
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class SkillInfoHolder {
    public int $id;
    public string $name;
    public string $description;
    public int $type;
    public float $cooldown;
    public array $energy;
    public array $ranks;
    public array $effects;
    public string $maxDescription;
    public int $maxCondition;
    public int $maxConditionValue;
    public array $maxEffects;
}
