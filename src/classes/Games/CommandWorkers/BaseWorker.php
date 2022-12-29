<?php

namespace Games\CommandWorkers;

/**
 * Description of BaseWorker
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
abstract class BaseWorker {
    
    private float $timingPrev = 0;

    abstract public function Process() : array;
    
    public function __construct(int $argc, array $argv) {
        
        if($argc > 2){
            
            $query = [];
            parse_str(implode('&', array_slice($argv, 2)), $query);
            
            foreach ($query as $key => $value) $this->$key = $value;
        }
    }
    
    protected function EchoMessage(string $label, mixed $contents = null) : void{
        
        $current = microtime(true);
        $timing = $current - $this->timingPrev;
        $this->timingPrev = $current;
        if($contents === null) return;
        
        $contents = [$label => $contents, 'Timing' => $timing.' seconds.'];
        echo json_encode($contents, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT).PHP_EOL.PHP_EOL;
    }
}
