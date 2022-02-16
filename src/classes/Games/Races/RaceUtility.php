<?php
namespace Games\Races;

use Games\Consts\RaceValue;
/**
 * Description of RaceUtility
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class RaceUtility {
    
    public static function RandomEnergy(int $slotNumber) : array{
        
        $count = RaceValue::BaseEnergyCount + $slotNumber;
        $energy = array_fill(0, RaceValue::EnergyTypeCount, 0);
        $max = RaceValue::EnergyTypeCount - 1;
        for($i = 0; $i < $count; ++$i){
            ++$energy[random_int(0, $max)];
        }
        
        return $energy;
    }
}
