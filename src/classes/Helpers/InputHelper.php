<?php

namespace Helpers;

use Consts\ErrorCode;
use Exception;
/**
 * Description of InputHelper
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class InputHelper {
    
    public static function post(string $name) : string|int|bool|float {
        
        return self::receive(INPUT_POST, $name);
    }
    
    public static function get(string $name) : string|int|bool|float {
        
        return self::receive(INPUT_GET, $name);
    }
    
    private static function receive(int $type, string $name) : string|int|bool|float {
        
        $value = filter_input($type, $name);
        if($value === false || $value === null) throw new Exception ('No parameter \''.$name.'\'', ErrorCode::ParamError);
        return $value;
    }
    
    public static function postArray(string $name) : array{
        $value = filter_input(INPUT_POST, $name, FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        if($value === false || $value === null) throw new Exception ('No parameter \''.$name.'\'', ErrorCode::ParamError);
        return $value;
    }
}
