<?php
namespace Games\Scenes;

use Games\Scenes\Holders\SceneClimateHolder;
use Generators\ConfigGenerator;
use Generators\DataGenerator;
/**
 * Description of ScenesUtility
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class SceneUtility {
    
    public static function CurrentClimate(array $climates) : SceneClimateHolder{
        
        $currentSceond = DataGenerator::TodaySecondByTimezone(ConfigGenerator::Instance()->TimezoneDefault);
        $currentClimate = end($climates);
        foreach($climates as $climate){
            if($currentSceond >= $climate->startTime){
                $currentClimate = clone $climate;
                break;
            }
        }
        return $currentClimate;
    }
}
