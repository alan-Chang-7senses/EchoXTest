<?php

namespace Games\Skills\Holders;

/**
 * Description of SkillEffectHolder
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class SkillEffectHolder {
    
    public int $type;
    public string|float|null $value; //暫時型別
    
    public function __construct(int $type, string|float|null $value) {
        $this->type = $type;
        $this->value = $value;
    }
}
