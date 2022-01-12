<?php

namespace Games\Players\Holders;

/**
 * Description of PlayerSkillMaxEffectHolder
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class PlayerSkillMaxEffectHolder {
    
    public int $type;
    public int $typeValue;
    public string|float|null $value; //暫時型別
    
    public function __construct(int $type, int $typeValue, string|float|null $value) {
        $this->type = $type;
        $this->typeValue = $typeValue;
        $this->value = $value;
    }
}
