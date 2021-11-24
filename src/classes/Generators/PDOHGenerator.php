<?php

namespace Generators;

use Helpers\PDOHelper;
/**
 * Description of PDOHGenerator
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class PDOHGenerator {
    
    private static PDOHGenerator $instance;
    
    public static function Instance() : PDOHGenerator{
        if(empty(self::$instance)) self::$instance = new PDOHGenerator ();
        return self::$instance;
    }
    
    public function __get($property) {
        
        $dbInfo = DBInfoGenerator::Instance()->$property;
        
        $this->$property = new PDOHelper($dbInfo);
        
        return $this->$property;
    }
}
