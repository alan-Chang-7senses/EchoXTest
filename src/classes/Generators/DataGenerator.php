<?php

namespace Generators;

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
}
