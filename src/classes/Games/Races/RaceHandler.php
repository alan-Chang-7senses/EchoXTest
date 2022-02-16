<?php

namespace Games\Races;

use Consts\ErrorCode;
use Exception;
use Games\Players\PlayerHandler;
use Games\Pools\RacePool;
use Games\Races\Holders\RaceInfoHolder;
use Generators\DataGenerator;
/**
 * Description of RaceHandler
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class RaceHandler {
    
    private RacePool $pool;
    private int|string $id;
    private PlayerHandler $playerHandler;
    private RaceInfoHolder $info;
    
    public function __construct(int|string $id) {
        $this->pool = RacePool::Instance();
        $this->id = $id;
        $this->ResetInfo();
    }
    
    public function __set(string $property, mixed $value) {
        
        $method = 'Save'.ucfirst($property);
        
        if(method_exists($this, $method )) $this->$method($value);
        else throw new Exception (get_called_class ().' no method '.$method, ErrorCode::SystemError);
        
        $this->ResetInfo();
    }
    
    private function ResetInfo(){
        $this->info = DataGenerator::ConventType($this->pool->{$this->id}, 'Games\Races\Holders\RaceInfoHolder');
    }
    
    public function GetInfo() : RaceInfoHolder{
        return $this->info;
    }
    
    public function SetPlayer(PlayerHandler $handler) : void{
        $this->playerHandler = $handler;
    }

    public function SaveRacePlayerIDs(array $ids) : void{
        $this->pool->Save($this->info->id, 'RacePlayerIDs', $ids);
    }
}
