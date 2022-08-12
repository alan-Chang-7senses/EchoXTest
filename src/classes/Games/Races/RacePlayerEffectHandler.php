<?php

namespace Games\Races;

use Consts\Globals;
use Consts\Sessions;
use Games\Consts\SkillValue;
use Games\Players\PlayerHandler;
use Games\Pools\RacePlayerEffectPool;
use stdClass;
use Games\Consts\RaceValue;
use Games\Consts\SceneValue;
use Games\Players\PlayerUtility;
use Games\Scenes\SceneHandler;
use Games\Users\UserHandler;

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
        $this->info = $this->pool->{$this->id};
    }
    
    public static function EffectPlayer(PlayerHandler $playerHandler, RacePlayerHandler $racePlayerHandler) : PlayerHandler{
        
        $current = $GLOBALS[Globals::TIME_BEGIN];
        $playerInfo = $playerHandler->GetInfo();
        $racePlayerInfo = $racePlayerHandler->GetInfo();
        
        $racePlayerEffectHandler = new RacePlayerEffectHandler($racePlayerInfo->id);
        foreach($racePlayerEffectHandler->info->list as $playerEffect){
            
            if($current > $playerEffect->EndTime) continue;
            
            $value = $playerEffect->EffectValue;
            match ($playerEffect->EffectType){
                SkillValue::EffectH => $playerHandler->offsetH += $value,
                SkillValue::EffectS => $playerHandler->offsetS += $value,
                SkillValue::EffectSPD => $playerInfo->velocity += $value,
                SkillValue::EffectPOW => $playerInfo->breakOut += $value,
                SkillValue::EffectFIG => $playerInfo->will += $value,
                SkillValue::EffectINT => $playerInfo->intelligent += $value,
                SkillValue::EffectSTA => $playerInfo->stamina += $value,
                SkillValue::EffectAdaptDune => $playerHandler->offsetDune += $value,
                SkillValue::EffectAdaptCraterLake => $playerHandler->offsetCraterLake += $value,
                SkillValue::EffectAdaptVolcano => $playerHandler->offsetVolcano += $value,
                SkillValue::EffectAdaptTailwind => $playerHandler->offsetTailwind += $value,
                SkillValue::EffectAdaptHeadwind => $playerHandler->offsetHeadwind += $value,
                SkillValue::EffectAdaptCrosswind => $playerHandler->offsetCrosswind += $value,
                SkillValue::EffectAdaptSunny => $playerHandler->offsetSunny += $value,
                SkillValue::EffectAdaptAurora => $playerHandler->offsetAurora += $value,
                SkillValue::EffectAdaptSandDust => $playerHandler->offsetSandDust += $value,
                SkillValue::EffectAdaptFlat => $playerHandler->offsetFlat += $value,
                SkillValue::EffectAdaptUpslope => $playerHandler->offsetUpslope += $value,
                SkillValue::EffectAdaptDownslope => $playerHandler->offsetDownslope += $value,
                SkillValue::EffectAdaptSun => self::DealWithSunValue($playerHandler,SceneValue::Sunshine,$value),
                SkillValue::EffectAdaptNight => self::DealWithSunValue($playerHandler,SceneValue::Backlight,$value),
                SkillValue::EffectHP => $racePlayerHandler->SaveData(['hp' => $racePlayerInfo->hp + $value * RaceValue::DivisorHP]),
                SkillValue::EffectEnergyAll => $racePlayerHandler->SaveData(['energy' => array_map(function($val) use ($value) { return $val + $value; }, $racePlayerInfo->energy)]),
                SkillValue::EffectEnergyRed => self::AddEnergy($racePlayerHandler, SkillValue::EnergyRed, $value),
                SkillValue::EffectEnergyYellow => self::AddEnergy($racePlayerHandler, SkillValue::EnergyYellow, $value),
                SkillValue::EffectEnergyBlue => self::AddEnergy($racePlayerHandler, SkillValue::EnergyBlue, $value),
                SkillValue::EffectEnergyGreen => self::AddEnergy($racePlayerHandler, SkillValue::EnergyGreen, $value),
                default => null
            };
        }
        
        return $playerHandler;
    }
    
    private static function AddEnergy(RacePlayerHandler $racePlayerHandler, int $type, float $value) : void{
        $energy = $racePlayerHandler->GetInfo()->energy;
        $energy[$type] += $value;
        $racePlayerHandler->SaveData(['energy' => $energy]);
    }

    private static function DealWithSunValue(PlayerHandler $playerHandler,int $requireLight, float $value) : void
    {        
        $currentSence = (new UserHandler($_SESSION[Sessions::UserID]))->GetInfo()->scene;
        $currentLight = (new SceneHandler($currentSence))->GetClimate()->lighting;
        if($currentLight == $requireLight && $playerHandler->GetInfo()->sun == $currentLight) $playerHandler->offsetSun += $value;
    }

    public function IsPlayerInEffect(array $effectTypes, $campareFunc) : bool
    {
        foreach($this->info->list as $effect)
        {
            foreach($effectTypes as $type)
            {
                $now = $GLOBALS[Globals::TIME_BEGIN];
                if($effect->EndTime > $now && $effect->StartTime <= $now && $effect->EffectType == $type)                   
                {
                    $val = $effect->EffectValue;
                    $reverseGroup = [SkillValue::EffectH];
                    foreach($reverseGroup as $reverseType)if($reverseType === $type)$val = -$val;
                    if($campareFunc($val,0))return true;
                }            
            }
        }
        return false;
    }


}
