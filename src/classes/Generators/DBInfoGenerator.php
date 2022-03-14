<?php

namespace Generators;

use Accessors\MemcacheAccessor;
use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Exception;
use Holders\DBInfo;
/**
 * Description of ConfigDBGenerator
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class DBInfoGenerator {
    
    private static DBInfoGenerator $instance;
    
    public static function Instance() : DBInfoGenerator {
        if(empty(self::$instance)) self::$instance = new DBInfoGenerator ();
        return self::$instance;
    }

    public function __construct() {
        
        $mainLabel = getenv(EnvVar::DBLabelMain);
        
        $holder = new DBInfo();
        $holder->Host = getenv(EnvVar::DBHost);
        $holder->Port = getenv(EnvVar::DBPort);
        $holder->Username = getenv(EnvVar::DBUsername);
        $holder->Password = getenv(EnvVar::DBPassword);
        $holder->Name = getenv(EnvVar::DBName);
        
        $this->$mainLabel = $holder;
    }
    
    public function __get($name) {
        
        $key = 'DBInfo'.$name;
        $memcache = MemcacheAccessor::Instance();
        $memData = $memcache->get($key);
        
        if($memData === false) $row = (new PDOAccessor(getenv(EnvVar::DBLabelMain)))->FromTable('DatabaseInfo')->WhereEqual('Label', $name)->Fetch();
        else $row = json_decode($memData);
        
        if(empty($row)) throw new Exception ('Database Info ['.$name.'] undefined', ErrorCode::ConfigError);
        
        $holder = new DBInfo();
        $holder->Host = $row->Host;
        $holder->Port = $row->Port;
        $holder->Username = $row->Username;
        $holder->Password = $row->Password;
        $holder->Name = $row->Name;

        $this->$name = $holder;

        if($memData === false) $memcache->set ($key, json_encode ($holder));
        
        return $this->$name;
    }
}
