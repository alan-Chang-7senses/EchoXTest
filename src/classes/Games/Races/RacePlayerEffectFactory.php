<?php

namespace Games\Races;

use Games\Players\Holders\PlayerInfoHolder;
use Games\Players\PlayerHandler;
use stdClass;
/**
 * Description of RacePlayerEffectFactory
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class RacePlayerEffectFactory {
    
    private PlayerHandler $playerHandler;
    private PlayerInfoHolder|stdClass $playerInfo;
    private RacePlayerHandler $racePlayerHandler;
    
    private array $types = [
        1 => 'EffectH',
        2 => 'EffectS',
        3 => 'EffectSPD',
        4 => 'EffectROW',
        5 => 'EffectFIG',
        6 => 'EffectINT',
        7 => 'EffectSTA',
    ];
    
    public function __construct(PlayerHandler $player, RacePlayerHandler $racePlayer) {
        $this->playerHandler = $player;
        $this->playerInfo = $player->GetInfo();
        $this->racePlayerHandler = $racePlayer;
    }
    
    public function Process() : void{
        
        $racePlayerID = $this->racePlayerHandler->GetInfo()->id;
        
        $current = microtime(true);
        $racePlayerEffectHandler = new RacePlayerEffectHandler($racePlayerID);
        foreach($racePlayerEffectHandler->info->list as $playerEffect){
            
            if($current > $playerEffect->EndTime) continue;
            
            $this->{$this->types[$playerEffect->EffectType]}($playerEffect->EffectValue);
        }
    }
    
    private function EffectH(float $value) : void{
        $this->playerHandler->offsetH += $value;
    }
    
    private function EffectS(float $value) : void{
        $this->playerHandler->offsetS += $value;
    }
    
    private function EffectSPD(float $value) : void{
        $this->playerInfo->velocity += $value;
    }
    
    private function EffectPOW(float $value) : void{
        $this->playerInfo->breakOut += $value;
    }
    
    private function EffectFIG(float $value) : void{
        $this->playerInfo->will += $value;
    }
    
    private function EffectINT(float $value) : void{
        $this->playerInfo->intelligent += $value;
    }
    
    private function EffectSTA(float $value) : void{
        $this->playerInfo->stamina += $value;
    }
}
