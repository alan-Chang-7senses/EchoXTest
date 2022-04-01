<?php

namespace Games\CommandWorkers;

/**
 * Description of BaseWorker
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
abstract class BaseWorker {
    
    abstract public function Process() : array;
    
    public function __construct(int $argc, array $argv) {
        
        if($argc > 2){
            
            $query = [];
            parse_str(implode('&', array_slice($argv, 2)), $query);
            
            foreach ($query as $key => $value) $this->$key = $value;
        }
    }
}
