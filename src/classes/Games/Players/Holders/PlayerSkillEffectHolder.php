<?php

namespace Games\Players\Holders;

/**
 * Description of PlayerSkillEffectHolder
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class PlayerSkillEffectHolder {
    
    public int $type;
    public string|float|null $value; //暫時型別
    
    public function __construct(int $type, string|float|null $value) {
        $this->type = $type;
        $this->value = $value;
    }
}
