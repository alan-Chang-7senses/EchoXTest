<?php

namespace Games\Players\Holders;

/**
 * Description of PlayerSkillHolder
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class PlayerSkillHolder {
    
    public int $id;
    public int $level;
    public int $slot;
    
    public function __construct(int $skillID, int $level, int $slot) {
        $this->id = $skillID;
        $this->level = $level;
        $this->slot = $slot;
    }
}
