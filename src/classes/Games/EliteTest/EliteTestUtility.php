<?php

namespace Games\EliteTest;

use Games\Accessors\EliteTestAccessor;
use Games\Consts\RaceValue;
use Games\EliteTest\EliteTestValues;
use Generators\ConfigGenerator;
/**
 * Description of EliteTestUtility
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class EliteTestUtility {
    
    public static function EndExpiredRace() : void {
        
        $current = microtime(true);
        $expried = $current - ConfigGenerator::Instance()->TimelimitElitetestRace;
        
        $accessor = new EliteTestAccessor();
        $raceIDs = $accessor->idsRaceByExpired($expried);
        
        if(count($raceIDs) == 0) return;
        
        $accessor->ModifyRaceByRaceIDs($raceIDs, ['Status' => EliteTestValues::RaceExpired, 'FinishTime' => $current]);
        $accessor->ModifyUserByRaces($raceIDs, ['Race' => RaceValue::NotInRace]);
    }
}
