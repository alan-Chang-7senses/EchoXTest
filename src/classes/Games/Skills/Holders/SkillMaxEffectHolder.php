<?php

namespace Games\Skills\Holders;

/**
 * Description of SkillMaxEffectHolder
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class SkillMaxEffectHolder {
    public int $id;
    public int $type;
    public int $target;
    public int $typeValue;
    public string|null $formula;
}
