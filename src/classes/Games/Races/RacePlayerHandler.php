<?php

namespace Games\Races;

use Games\Pools\RacePlayerPool;
use Games\Races\Holders\RacePlayerHolder;
use Generators\DataGenerator;
/**
 * Description of RacePlayerHandler
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class RacePlayerHandler {
    
    private RacePlayerPool $pool;
    private int|string $id;
    private RacePlayerHolder $info;
    
    public function __construct(int|string $id) {
        $this->pool = RacePlayerPool::Instance();
        $this->id = $id;
        $this->ResetInfo();
    }
    
    public function ResetInfo() : void{
        $this->info = DataGenerator::ConventType($this->pool->{$this->id}, 'Games\Races\Holders\RacePlayerHolder');
    }

    public function GetInfo() : RacePlayerHolder{
        return $this->info;
    }
    
    public function SaveData(array $bind) : void{
        $this->pool->Save($this->id, 'Data', $bind);
        $this->ResetInfo();
    }
}
