<?php

namespace Games\Players\Holders;

/**
 * Description of PlayerSkillHolder
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class PlayerSkillHolder {
    
    public int $skillID;
    public int $level;
    public int $slot;
    
    public function __construct(int $skillID, int $level, int $slot) {
        $this->skillID = $skillID;
        $this->level = $level;
        $this->slot = $slot;
    }
}
