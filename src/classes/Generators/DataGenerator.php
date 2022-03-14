<?php

namespace Generators;

use DateTime;
use DateTimeZone;
use stdClass;
use Consts\ErrorCode;
use Exception;
/**
 * Description of DataGenerator
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class DataGenerator {
    
    public static function RandomString(int $length) : string{
        
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMONPQRSTUVWXYZ';
        
        $string = '';
        for($i = 0 ; $i < $length ; ++$i){
            $string .= $chars[rand(0, strlen($chars) - 1)];
        }
        
        return $string;
    }
    
    public static function TodaySecondByTimezone(int $timezone) : int {
        return time() - (new DateTime('today midnight', self::DateTimeZone($timezone)))->getTimestamp();
    }

    private static function DateTimeZone(int $timezone) : DateTimeZone{
        return new DateTimeZone('GMT'.($timezone >= 0 ? '+'.$timezone : $timezone));
    }
    
    public static function ConventType(mixed $obj, string $classFull) : mixed {
        $result = new $classFull();
        foreach(get_object_vars($obj) as $key => $value) $result->$key = $value;
        return $result;
    }
    
    public static function ExistProperty(stdClass $obj, string $property) : void {
        if(!isset($obj->$property)) throw new Exception ('The property \''.$property.'\' not exist', ErrorCode::ParamError);
    }
    
    public static function ExistProperties(stdClass $obj, array $properties) : void {
        foreach ($properties as $property) {
            if(!isset($obj->$property)) throw new Exception ('The property \''.$property.'\' not exist', ErrorCode::ParamError);
        }
    }
    
    public static function UserIP() : string{
        if (!empty($_SERVER["HTTP_CLIENT_IP"]))  return $_SERVER["HTTP_CLIENT_IP"];
        elseif(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) return $_SERVER["HTTP_X_FORWARDED_FOR"];
        else return $_SERVER["REMOTE_ADDR"];
    }
}
