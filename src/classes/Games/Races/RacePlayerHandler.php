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
    
    private function ResetInfo() : RacePlayerHolder|stdClass{
        $this->info = $this->pool->{$this->id};
        return $this->info;
    }

    public function GetInfo() : RacePlayerHolder|stdClass{
        return $this->info;
    }
    
    public function SaveData(array $bind) : RacePlayerHolder|stdClass{
        $this->pool->Save($this->id, 'Data', $bind);
        return $this->ResetInfo();
    }
    
    public function EnoughEnergy(array $energy) : bool{
        foreach ($this->info->energy as $key => $value) {
            if($value < $energy[$key]) return false;
        }
        return true;
    }
    
    public function PayEnergy(array $energy) : RacePlayerHolder|stdClass{
        return $this->SaveData(['energy' => array_map(function($original, $pay){
            return $original - $pay;
        }, $this->info->energy, $energy)]);
    }

}
