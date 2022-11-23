<?php

namespace Generators;

use Consts\ErrorCode;
use DateTime;
use DateTimeZone;
use Exception;
use stdClass;
/**
 * Description of DataGenerator
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class DataGenerator {
    
    public static function TimestampByTimezone(string $datetime, int $timezone): int {
        $dateTimezone = new DateTimeZone('GMT' . ($timezone >= 0 ? '+' . $timezone : $timezone));
        $datetime = new DateTime($datetime, $dateTimezone);
        return $datetime->getTimestamp();
    }
        
    public static function TimestringByTimezone(int $timestamp, int $timezone, string $formatString): string {
        $dateTimezone = new DateTimeZone('GMT' . ($timezone >= 0 ? '+' . $timezone : $timezone));
        $date = new DateTime("@" . $timestamp);
        $date->setTimezone($dateTimezone);
        return $date->format($formatString);
    }
    
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

    public static function SetTodaySecondsToTimezone(int $seconds, int $timezone) : int {
        return $seconds + (new DateTime('today midnight', self::DateTimeZone($timezone)))->getTimestamp();
    }

    
    public static function TodayHourByTimezone(int $timezone) : int{
        return (new DateTime('now', self::DateTimeZone($timezone)))->format('H');
    }

    private static function DateTimeZone(int $timezone) : DateTimeZone{
        return new DateTimeZone('GMT'.($timezone >= 0 ? '+'.$timezone : $timezone));
    }
    
    public static function ExistProperty(stdClass $obj, string $property) : void {
        if(!isset($obj->$property)) throw new Exception ('The property \''.$property.'\' not exist', ErrorCode::ParamError);
    }
    
    public static function ExistProperties(stdClass $obj, array $properties) : void {
        foreach ($properties as $property) {
            if(!isset($obj->$property)) throw new Exception ('The property \''.$property.'\' not exist', ErrorCode::ParamError);
        }
    }
    
    public static function ValidProperties(stdClass $obj, array $validNames) : void{
        $diff = array_diff(array_keys(get_object_vars($obj)), $validNames);
        if(!empty($diff)) throw new Exception ('The property \''.array_shift ($diff).'\' is invalid', ErrorCode::ParamError);
    }

    public static function UserIP() : string{
        if (!empty($_SERVER["HTTP_CLIENT_IP"]))  return $_SERVER["HTTP_CLIENT_IP"];
        elseif(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) return $_SERVER["HTTP_X_FORWARDED_FOR"];
        else return $_SERVER["REMOTE_ADDR"];
    }
    
    public static function GuidV4(string|null $data = null) : string{
        // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
        $data = $data ?? random_bytes(16);
        assert(strlen($data) == 16);

        // Set version to 0100
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        // Set bits 6-7 to 10
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

        // Output the 36 character UUID.
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}
