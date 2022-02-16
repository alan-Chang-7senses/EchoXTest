<?php

namespace Games\Races;

use Consts\ErrorCode;
use Exception;
use Games\Consts\RaceValue;
use Games\Consts\SceneValue;
use Games\Players\PlayerHandler;
use Games\Pools\RacePool;
use Games\Races\Holders\RaceInfoHolder;
use Games\Scenes\SceneHandler;
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
    private RacePlayerHandler $racePlayerHandler;
    private SceneHandler $sceneHandler;
    
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
        $this->racePlayerHandler = new RacePlayerHandler($this->info->racePlayers->{$handler->GetInfo()->id});
    }
    
    public function SetSecne(SceneHandler $handler) : void{
        $this->sceneHandler = $handler;
    }

    public function SaveRacePlayerIDs(array $ids) : void{
        $this->pool->Save($this->info->id, 'RacePlayerIDs', $ids);
    }
    
    public function ValueS() : float{
        
        $trackShape = $this->racePlayerHandler->GetInfo()->trackShape;
        
        $result = match (true) {
            $this->playerHandler->GetInfo()->hp <= 0 => $this->DepletedValueS() ,
            $trackShape == SceneValue::Straight => $this->StraightValueS(),
            default => $this->CurvedValueS(),
        };
        
        return $result * $this->RhythmValueS();
    }
    
    private function StraightValueS() : float{
        
        $scene = $this->sceneHandler->GetInfo();
        $climate = $this->sceneHandler->GetClimate();
        $player = $this->playerHandler->GetInfo();
        $racePlayer = $this->racePlayerHandler->GetInfo();
        $slope = RaceValue::TrackType[$racePlayer->trackType];
        
        return RaceValue::ClimateAccelerations[$climate->weather] * 
                ($player->velocity / ($slope * 2) + $player->breakOut / ($slope * 6)) * 
                ((
                    $this->playerHandler->GetEnvValue($scene->env) + 
                    $this->playerHandler->GetClimateValue($climate->weather) + 
                    $this->playerHandler->GetSunValue($climate->lighting) + 
                    $this->playerHandler->GetTerrainValue($racePlayer->trackType) + 
                    $this->playerHandler->GetWinValue($this->PlayerWindDirection()) - 400
                ) / 100) + $this->WindEffectValue();
    }
    
    private function CurvedValueS() : float{
        
        $scene = $this->sceneHandler->GetInfo();
        $climate = $this->sceneHandler->GetClimate();
        $player = $this->playerHandler->GetInfo();
        $racePlayer = $this->racePlayerHandler->GetInfo();
        $slope = RaceValue::TrackType[$racePlayer->trackType];
        
        return RaceValue::ClimateAccelerations[$climate->weather] * 
                ($player->velocity / ($slope * 2) + $player->intelligent / ($slope * 6)) * 
                ((
                    $this->playerHandler->GetEnvValue($scene->env) + 
                    $this->playerHandler->GetClimateValue($climate->weather) + 
                    $this->playerHandler->GetSunValue($climate->lighting) + 
                    $this->playerHandler->GetTerrainValue($racePlayer->trackType) + 
                    $this->playerHandler->GetWinValue($this->PlayerWindDirection()) - 400
                ) / 100) + $this->WindEffectValue();
    }
    
    private function DepletedValueS() : float{
        
        $scene = $this->sceneHandler->GetInfo();
        $climate = $this->sceneHandler->GetClimate();
        $player = $this->playerHandler->GetInfo();
        $racePlayer = $this->racePlayerHandler->GetInfo();
        $slope = RaceValue::TrackType[$racePlayer->trackType];
        
        return RaceValue::ClimateAccelerations[$climate->weather] * 
                ($player->velocity + $player->will) / ($slope * 6) * 
                ((
                    $this->playerHandler->GetEnvValue($scene->env) + 
                    $this->playerHandler->GetClimateValue($climate->weather) + 
                    $this->playerHandler->GetSunValue($climate->lighting) + 
                    $this->playerHandler->GetTerrainValue($racePlayer->trackType) + 
                    $this->playerHandler->GetWinValue($this->PlayerWindDirection()) - 400
                ) / 100) + $this->WindEffectValue();
    }
    
    public function ValueH() : float{
        
        $scene = $this->sceneHandler->GetInfo();
        $climate = $this->sceneHandler->GetClimate();
        $player = $this->playerHandler->GetInfo();
        $racePlayer = $this->racePlayerHandler->GetInfo();
        $slope = RaceValue::TrackType[$racePlayer->trackType];
        
        $result = RaceValue::ClimateLoses[$climate->weather] + 
                $slope * $this->ValueS() / $player->velocity * 
                ( 100 / (
                    $this->playerHandler->GetEnvValue($scene->env) + 
                    $this->playerHandler->GetClimateValue($climate->weather) + 
                    $this->playerHandler->GetSunValue($climate->lighting) + 
                    $this->playerHandler->GetTerrainValue($racePlayer->trackType) + 
                    $this->playerHandler->GetWinValue($this->PlayerWindDirection()) - 400
                ));
        
        return $result * $this->RhythmValueH();
    }
    
    /**
     * 角色風向
     * @return int
     */
    private function PlayerWindDirection() : int{
        
        return match (abs($this->info->windDirection - $this->racePlayerHandler->GetInfo()->direction)) {
            RaceValue::WindCheckPositive => SceneValue::Tailwind,
            RaceValue::WindCheckReverse => SceneValue::Headwind,
            default => SceneValue::Crosswind,
        };
    }
    
    /**
     * 風向影響值
     * @return float
     */
    private function WindEffectValue() : float {
        
        return RaceValue::WindEffectFactor[$this->PlayerWindDirection()] * $this->sceneHandler->GetClimate()->windSpeed;
    }
    
    /**
     * 比賽節奏 S 耕倍數
     * @return float
     */
    private function RhythmValueS() : float{
        return match ($this->racePlayerHandler->GetInfo()->rhythm) {
            RaceValue::Sprint => 1.2,
            RaceValue::RetainStrength => 0.8,
            default => 1,
        };
    }
    
    /**
     * 比賽節奏 H 耕倍數
     * @return float
     */
    private function RhythmValueH() : float{
        return match ($this->racePlayerHandler->GetInfo()->rhythm) {
            RaceValue::Sprint => 1.2,
            RaceValue::RetainStrength => 0.8,
            default => 1,
        };
    }
}
