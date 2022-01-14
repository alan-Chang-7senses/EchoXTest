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
    public int $type;
    public int $level;
    public array $ranks;
    /** @var array SkillEffectHolder */
    public array $effects;
    /** @var array SkillMaxEffectHolder */
    public array $maxEffects;
}
