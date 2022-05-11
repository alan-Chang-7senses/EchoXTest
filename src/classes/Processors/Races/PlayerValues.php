<?php

namespace Processors\Races;

/**
 * Description of PlayerValues
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class PlayerValues extends BasePlayerValues{
    
    public function GetPlayerID(): int {
        return $this->userInfo->player;
    }
}
