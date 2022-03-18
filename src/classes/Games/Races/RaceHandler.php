<?php

namespace Games\Races;

use Games\Accessors\RaceAccessor;
use Games\Consts\RaceValue;
use Games\Consts\SceneValue;
use Games\Exceptions\RaceException;
use Games\Players\PlayerHandler;
use Games\Pools\RacePool;
use Games\Races\Holders\RaceInfoHolder;
use Games\Races\Holders\RacePlayerHolder;
use Games\Scenes\SceneHandler;
use Games\Users\UserHandler;
use Generators\DataGenerator;
use Helpers\LogHelper;
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
    
    private function ResetInfo() : void{
        $this->info = DataGenerator::ConventType($this->pool->{$this->id}, 'Games\Races\Holders\RaceInfoHolder');
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
        $this->ResetInfo();
    }
    
    public function GetInfo() : RaceInfoHolder{
        return $this->info;
    }

    public function GetRacePlayerInfo() : RacePlayerHolder {
        return $this->racePlayerHandler->GetInfo();
    }
    
    public function SaveRacePlayer(array $bind) : void{
        $this->racePlayerHandler->SaveData($bind);
    }
    
    public function Finish() : void{
        
        $result = (new RaceAccessor())->FinishRaceByRaceID($this->id, RaceValue::StatusFinish);
        if($result[0]->step != RaceValue::StepFinishSuccess){
            LogHelper::Extra('RaceFinishFailure', [$result[0]]);
            throw new RaceException (RaceException::FinishFailure);
        }
        
        foreach ($this->info->racePlayers as $racePlayerID) {
            $racePlayerHandler = new RacePlayerHandler($racePlayerID);
            (new UserHandler($racePlayerHandler->GetInfo()->user))->LeaveRace();
            $racePlayerHandler->Delete();
        }
        $this->pool->Delete($this->id);
        unset($this->info);
    }

    public function ValueS() : float {
        
        $scene = $this->sceneHandler->GetInfo();
        $climate = $this->sceneHandler->GetClimate();
        $player = $this->playerHandler->GetInfo();
        $racePlayer = $this->racePlayerHandler->GetInfo();
        $slope = RaceValue::TrackType[$racePlayer->trackType];
        
        $result = 0;
        if($racePlayer->trackShape == SceneValue::Straight){
            
            if($racePlayer->hp > 0){
                $result = RaceValue::ClimateAccelerations[$climate->weather] * 
                ($player->breakOut / ($slope * 5) + $player->velocity / ($slope * 15)) * 
                ((
                    $this->playerHandler->GetEnvValue($scene->env) + 
                    $this->playerHandler->GetClimateValue($climate->weather) + 
                    $this->playerHandler->GetSunValue($climate->lighting) + 
                    $this->playerHandler->GetTerrainValue($racePlayer->trackType) + 
                    $this->playerHandler->GetWinValue($this->PlayerWindDirection()) - 400
                ) / 100);
            }else{
                $result = RaceValue::ClimateAccelerations[$climate->weather] * 
                ($player->breakOut + $player->will) / ($slope * 15) * 
                ((
                    $this->playerHandler->GetEnvValue($scene->env) + 
                    $this->playerHandler->GetClimateValue($climate->weather) + 
                    $this->playerHandler->GetSunValue($climate->lighting) + 
                    $this->playerHandler->GetTerrainValue($racePlayer->trackType) + 
                    $this->playerHandler->GetWinValue($this->PlayerWindDirection()) - 400
                ) / 100);
            }
            
        }else{
            
            if($racePlayer->hp > 0){
                $result = RaceValue::ClimateAccelerations[$climate->weather] * 
                ($player->velocity / ($slope * 5) + $player->breakOut / ($slope * 15)) * 
                ((
                    $this->playerHandler->GetEnvValue($scene->env) + 
                    $this->playerHandler->GetClimateValue($climate->weather) + 
                    $this->playerHandler->GetSunValue($climate->lighting) + 
                    $this->playerHandler->GetTerrainValue($racePlayer->trackType) + 
                    $this->playerHandler->GetWinValue($this->PlayerWindDirection()) - 400
                ) / 100);
            }else{
                $result = RaceValue::ClimateAccelerations[$climate->weather] * 
                ($player->velocity + $player->will) / ($slope * 15) * 
                ((
                    $this->playerHandler->GetEnvValue($scene->env) + 
                    $this->playerHandler->GetClimateValue($climate->weather) + 
                    $this->playerHandler->GetSunValue($climate->lighting) + 
                    $this->playerHandler->GetTerrainValue($racePlayer->trackType) + 
                    $this->playerHandler->GetWinValue($this->PlayerWindDirection()) - 400
                ) / 100);
            }
        }
        
        return ($result + $this->WindEffectValue()) * $this->RhythmValueS();
    }
    
    public function ValueH() : float{
        
        $scene = $this->sceneHandler->GetInfo();
        $climate = $this->sceneHandler->GetClimate();
        $player = $this->playerHandler->GetInfo();
        $racePlayer = $this->racePlayerHandler->GetInfo();
        $slope = RaceValue::TrackType[$racePlayer->trackType];
        
        $result = match ($racePlayer->trackType){
            SceneValue::Upslope => RaceValue::ClimateLoses[$climate->weather] + 
                    4 * $slope * $this->ValueS() / $player->will * 
                    ( 100 / (
                        $this->playerHandler->GetEnvValue($scene->env) + 
                        $this->playerHandler->GetClimateValue($climate->weather) + 
                        $this->playerHandler->GetSunValue($climate->lighting) + 
                        $this->playerHandler->GetTerrainValue($racePlayer->trackType) + 
                        $this->playerHandler->GetWinValue($this->PlayerWindDirection()) - 400
                    )),
            SceneValue::Downslope => RaceValue::ClimateLoses[$climate->weather] + 
                    4 * $slope * $this->ValueS() / $player->intelligent * 
                    ( 100 / (
                        $this->playerHandler->GetEnvValue($scene->env) + 
                        $this->playerHandler->GetClimateValue($climate->weather) + 
                        $this->playerHandler->GetSunValue($climate->lighting) + 
                        $this->playerHandler->GetTerrainValue($racePlayer->trackType) + 
                        $this->playerHandler->GetWinValue($this->PlayerWindDirection()) - 400
                    )),
            default => RaceValue::ClimateLoses[$climate->weather] + 
                    4 * $slope * 2 * $this->ValueS() / ($player->intelligent + $player->will) * 
                    ( 100 / (
                        $this->playerHandler->GetEnvValue($scene->env) + 
                        $this->playerHandler->GetClimateValue($climate->weather) + 
                        $this->playerHandler->GetSunValue($climate->lighting) + 
                        $this->playerHandler->GetTerrainValue($racePlayer->trackType) + 
                        $this->playerHandler->GetWinValue($this->PlayerWindDirection()) - 400
                    ))
        };
        
        return $result * $this->RhythmValueH();
    }

    public function StartSecond() : float{
        $scene = $this->sceneHandler->GetInfo();
        $climate = $this->sceneHandler->GetClimate();
        $player = $this->playerHandler->GetInfo();
        return 0.01 * ( 100 / $player->intelligent + 100 / $this->playerHandler->GetEnvValue($scene->env) + 100 / $this->playerHandler->GetClimateValue($climate->weather) + 100 / $this->playerHandler->GetSunValue($climate->lighting));
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
