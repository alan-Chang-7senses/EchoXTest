<?php

namespace Generators;

use Consts\EnvVar;
use Helpers\PDOHelper;
use Holders\DBInfo;
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
        
        $dbInfo = new DBInfo();
        $dbInfo->Host = getenv(EnvVar::DBs[$property][EnvVar::DBHost]);
        $dbInfo->Port = getenv(EnvVar::DBs[$property][EnvVar::DBPort]);
        $dbInfo->Username = getenv(EnvVar::DBs[$property][EnvVar::DBUsername]);
        $dbInfo->Password = getenv(EnvVar::DBs[$property][EnvVar::DBPassword]);
        $dbInfo->Name = getenv(EnvVar::DBs[$property][EnvVar::DBName]);
        
        $this->$property = new PDOHelper($dbInfo);
        
        return $this->$property;
    }
}
