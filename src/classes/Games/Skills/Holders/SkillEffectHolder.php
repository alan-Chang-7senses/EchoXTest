<?php

namespace Games\Skills\Holders;

use stdClass;
/**
 * Description of SkillEffectHolder
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class SkillEffectHolder extends stdClass{
    
    public int $id;
    public int $type;
    public int $duration;
    public string $formula;
}
