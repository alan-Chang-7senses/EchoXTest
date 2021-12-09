<?php

namespace Generators;

use DateTime;
use DateTimeZone;
/**
 * Description of DataGenerator
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class DataGenerator {
    
    public static function RandomString(int $length) : string{
        
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMONPQRSTUVWXYZ';
        
        $string = '';
        for($i = 0 ; $i < $length ; ++$i){
            $string .= $characters[rand(0, strlen($characters) - 1)];
        }
        
        return $string;
    }
    
    public static function TodaySecondByTimezone(int $timezone) : int {
        return time() - (new DateTime('today midnight', self::DateTimeZone($timezone)))->getTimestamp();
    }

    private static function DateTimeZone(int $timezone) : DateTimeZone{
        return new DateTimeZone('GMT'.($timezone >= 0 ? '+'.$timezone : $timezone));
    }
}
