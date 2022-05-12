<?php

namespace Games\Skills\Holders;

use stdClass;
/**
 * Description of SkillMaxEffectHolder
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class SkillMaxEffectHolder extends stdClass{
    public int $id;
    public int $type;
    public int $target;
    public string|null $formula;
}
