<?php

namespace Games\Skills;

use Games\Consts\RaceValue;
use Games\Consts\SkillValue;
use Games\Players\Holders\PlayerInfoHolder;
use Games\Players\PlayerHandler;
use Games\Races\RacePlayerHandler;
use Games\Races\RacePlayerSkillHandler;
use Games\Skills\SkillHandler;
use stdClass;
/**
 * Description of SkillEffectFactor
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class SkillEffectFactory {
    
    private PlayerHandler $playerHandler;
    private PlayerInfoHolder|stdClass $playerInfo;
    private RacePlayerHandler $racePlayerHandler;
    
    private array $types = [
        101 => 'ReduceH',
        102 => 'IncreaseS',
        103 => 'RestoreHP',
        201 => 'UpdateSPD',
        202 => 'UpdatePOW',
        203 => 'UpdateFIG',
        204 => 'UpdateINT',
        205 => 'UpdateSTA',
    ];
    
    public function __construct(PlayerHandler $player, RacePlayerHandler $racePlayer) {
        $this->playerHandler = $player;
        $this->playerInfo = $player->GetInfo();
        $this->racePlayerHandler = $racePlayer;
    }
    
    public function Process() : PlayerHandler{
        
        $racePlayerID = $this->racePlayerHandler->GetInfo()->id;
        
        $racePlayerSkillHandler = new RacePlayerSkillHandler($racePlayerID);
        foreach($racePlayerSkillHandler->info as $serial => $row){
            
            if($row->Status == RaceValue::StatusFinish) continue;
            
            $skillHandler = new SkillHandler($row->SkillID);
            $duration = $skillHandler->GetInfo()->duration;
            
            if($duration > SkillValue::DurationOnce && microtime(true) > $row->CreateTime + $duration) continue;
            
            $skillHandler->playerHandler = $this->playerHandler;
            $skillHandler->racePlayerHandler = $this->racePlayerHandler;
            
            $effects = $skillHandler->GetEffects();
            foreach ($effects as $effect){
                $this->{$this->types[$effect['type']]}($effect['formulaValue']);
            }
            
            if($duration == SkillValue::DurationOnce) $racePlayerSkillHandler->Finish ($serial);
        }
        
        return $this->playerHandler;
    }
    
    private function ReduceH(float $value) : void{
        $this->playerHandler->offsetH -= $value;
    }
    
    private function IncreaseS(float $value) : void{
        $this->playerHandler->offsetS += $value;
    }
    
    private function RestoreHP(float $value) : void{
        $this->racePlayerHandler->SaveData(['hp' => (int)($this->racePlayerHandler->GetInfo()->hp + $value)]);
    }
    
    private function UpdateSPD(float $value) : void{
        $this->playerInfo->velocity = $value;
    }
    
    private function UpdatePOW(float $value) : void{
        $this->playerInfo->breakOut = $value;
    }
    
    private function UpdateFIG(float $value) : void{
        $this->playerInfo->will = $value;
    }
    
    private function UpdateINT(float $value) : void{
        $this->playerInfo->intelligent = $value;
    }
    
    private function UpdateSTA(float $value) : void{
        $this->playerInfo->stamina = $value;
    }
}
