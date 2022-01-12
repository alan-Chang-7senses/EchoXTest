<?php

namespace Games\Players\Holders;

/**
 * Description of PlayerSkillHolder
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class PlayerSkillHolder {
    public int $id;
    public string $name;
    public int $type;
    public int $level;
    public array $ranks;
    /** @var array PlayerSkillEffectHolder */
    public array $effects;
    /** @var array PlayerSkillMaxEffectHolder */
    public array $maxEffects;
}
