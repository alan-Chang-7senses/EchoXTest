<?php

namespace Games\Races;

use Games\Players\PlayerHandler;
use Games\Pools\RacePlayerPool;
use Games\Races\Holders\RacePlayerHolder;
use Games\Scenes\SceneHandler;
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
    
    public function PayEnergy(array $energy) : RacePlayerHolder|stdClass{
        return $this->SaveData(['energy' => array_map(function($original, $pay){
            return $original - $pay;
        }, $this->info->energy, $energy)]);
    }

    public function IsPlayerMatchLight(PlayerHandler $playerHandler)
    {
        $raceInfo = (new RaceHandler($this->info->race))->GetInfo();
        $climate = (new SceneHandler($raceInfo->scene))->GetClimate();
        $playerInfo = $playerHandler->GetInfo();
        return  $climate->lighting === $playerInfo->sun;
    }
}
