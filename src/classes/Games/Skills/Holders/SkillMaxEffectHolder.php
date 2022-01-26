<?php

namespace Games\Skills\Holders;

/**
 * Description of SkillMaxEffectHolder
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class SkillMaxEffectHolder {
    
    public int $type;
    public int $typeValue;
    public string|float|null $value; //暫時型別
    
    public function __construct(int $type, int $typeValue, string|float|null $value) {
        $this->type = $type;
        $this->typeValue = $typeValue;
        $this->value = $value;
    }
}
