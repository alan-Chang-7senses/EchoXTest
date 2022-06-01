<?php

namespace Processors\Races;

use Helpers\InputHelper;
/**
 * Description of HostedLaunchSkill
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class HostedLaunchSkill extends BaseLaunchSkill{
    
    public function GetPlayerID(): int {
        return InputHelper::post('player');
    }
}
