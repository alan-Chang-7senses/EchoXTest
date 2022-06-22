<?php

namespace Games\CommandWorkers;

use Accessors\PDOAccessor;
use Consts\EnvVar;
/**
 * Description of CurrentSessions
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class CurrentSessions extends BaseWorker{
    
    public function Process(): array {
        
        $accessor = new PDOAccessor(EnvVar::DBMain);
        
        return $accessor->FromTable('Sessions')->FetchAll();
    }
}
