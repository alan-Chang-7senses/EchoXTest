<?php

namespace Processors\Races;
/**
 * Description of LaunchSkill
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class LaunchSkill extends BaseLaunchSkill{
    
    public function GetPlayerID(): int {
        return $this->GetCurrentPlayerID();
        // return $this->userInfo->player;
    }
}
