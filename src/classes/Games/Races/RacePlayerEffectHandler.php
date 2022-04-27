<?php

namespace Games\Races;

use Consts\Globals;
use Games\Consts\RaceValue;
use Games\Players\PlayerHandler;
use Games\Pools\RacePlayerEffectPool;
use stdClass;
/**
 * Description of RacePlayerEffectHandler
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class RacePlayerEffectHandler {
    
    private int|string $id;
    private RacePlayerEffectPool $pool;
    
    public stdClass $info;
    
    public function __construct(int|string $id) {
        $this->id = $id;
        $this->pool = RacePlayerEffectPool::Instance();
        $this->info = $this->pool->$id;
    }
    
    public function AddAll(array $binds) : void{
        $this->pool->Save($this->id, 'NewData', $binds);
    }
    
    public static function EffectPlayer(PlayerHandler $playerHandler, RacePlayerHandler $racePlayerHandler) : PlayerHandler{
        
        $current = $GLOBALS[Globals::TIME_BEGIN];
        $playerInfo = $playerHandler->GetInfo();
        
        $racePlayerEffectHandler = new RacePlayerEffectHandler($racePlayerHandler->GetInfo()->id);
        foreach($racePlayerEffectHandler->info->list as $playerEffect){
            
            if($current > $playerEffect->EndTime) continue;
            
            $value = $playerEffect->EffectValue;
            match ($playerEffect->EffectType){
                RaceValue::PlayerEffectH => $playerHandler->offsetH += $value,
                RaceValue::PlayerEffectS => $playerHandler->offsetS += $value,
                RaceValue::PlayerEffectSPD => $playerInfo->velocity += $value,
                RaceValue::PlayerEffectPOW => $playerInfo->breakOut += $value,
                RaceValue::PlayerEffectFIG => $playerInfo->will += $value,
                RaceValue::PlayerEffectINT => $playerInfo->intelligent += $value,
                RaceValue::PlayerEffectSTA => $playerInfo->stamina += $value,
            };
        }
        
        return $playerHandler;
    }
}
