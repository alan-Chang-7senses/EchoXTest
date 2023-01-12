<?php

namespace Games\CommandWorkers;

use Games\Accessors\AccessorFactory;

class RefreshPoint extends BaseWorker
{
    public function Process(): array
    {
        $logAccessor = AccessorFactory::Log();
        
        return [];
    }
}