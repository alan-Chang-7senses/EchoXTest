<?php

namespace Processors\Races;

use Helpers\InputHelper;
/**
 * Description of HostedPlayerValues
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class HostedPlayerValues extends BasePlayerValues {
    
    public function GetPlayerID(): int {
        return InputHelper::post('player');
    }
}
