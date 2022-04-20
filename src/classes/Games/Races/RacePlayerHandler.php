<?php

namespace Games\Races;

use Games\Pools\RacePlayerPool;
use Games\Races\Holders\RacePlayerHolder;
use stdClass;
/**
 * Description of RacePlayerHandler
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class RacePlayerHandler {
    
    private RacePlayerPool $pool;
    private int|string $id;
    private RacePlayerHolder|stdClass $info;
    
    public function __construct(int|string $id) {
        $this->pool = RacePlayerPool::Instance();
        $this->id = $id;
        $this->ResetInfo();
    }
    
    public function ResetInfo() : void{
        $this->info = $this->pool->{$this->id};
    }

    public function GetInfo() : RacePlayerHolder|stdClass{
        return $this->info;
    }
    
    public function SaveData(array $bind) : void{
        $this->pool->Save($this->id, 'Data', $bind);
        $this->ResetInfo();
    }
    
    public function Delete() : void{
        $this->pool->Delete($this->id);
        unset($this->info);
    }
    
    public function EnoughEnergy(array $energy) : bool{
        foreach ($this->info->energy as $key => $value) {
            if($value < $energy[$key]) return false;
        }
        return true;
    }
}
