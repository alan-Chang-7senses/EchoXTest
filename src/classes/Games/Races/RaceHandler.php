<?php

namespace Games\Races;

use Games\Accessors\RaceAccessor;
use Games\Consts\RaceValue;
use Games\Consts\SceneValue;
use Games\Consts\SkillValue;
use Games\Exceptions\RaceException;
use Games\Players\PlayerHandler;
use Games\Pools\RacePool;
use Games\Races\Holders\RaceInfoHolder;
use Games\Races\Holders\RacePlayerHolder;
use Games\Scenes\SceneHandler;
use Games\Skills\SkillHandler;
use Games\Users\UserHandler;
use Generators\ConfigGenerator;
use Helpers\LogHelper;
use stdClass;
/**
 * Description of RaceHandler
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class RaceHandler {
    
    private RacePool $pool;
    private int|string $id;
    private RaceInfoHolder|stdClass $info;
    private PlayerHandler $playerHandler;
    private RacePlayerHandler $racePlayerHandler;
    private SceneHandler $sceneHandler;
    
    public function __construct(int|string $id) {
        $this->pool = RacePool::Instance();
        $this->id = $id;
        $this->ResetInfo();
    }
    
    private function ResetInfo() : RaceInfoHolder|stdClass{
        $this->info = $this->pool->{$this->id};
        return $this->info;
    }
    
    public function SetPlayer(PlayerHandler $handler) : RacePlayerHandler{
        $playerInfo = $handler->GetInfo();
        $racePlayerID = $this->info->racePlayers->{$playerInfo->id} ?? null;
        if($racePlayerID === null) throw new RaceException(RaceException::PlayerNotInThisRace);
        $this->playerHandler = $handler;
        $this->racePlayerHandler = new RacePlayerHandler($racePlayerID);
        return $this->racePlayerHandler;
    }
    
    public function SetSecne(SceneHandler $handler) : void{
        $this->sceneHandler = $handler;
    }
    
    public function SaveData(array $bind) : RaceInfoHolder|stdClass{
        $this->pool->Save($this->id, 'Data', $bind);
        return $this->ResetInfo();
    }
    
    public function GetInfo() : RaceInfoHolder|stdClass{
        return $this->info;
    }

    public function GetRacePlayerInfo() : RacePlayerHolder|stdClass {
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
        
        $climatAccelerations = RaceValue::ClimateAccelerations[$this->info->weather];
        $envValue = $this->playerHandler->GetEnvValue($scene->env);
        $climateValue = $this->playerHandler->GetClimateValue($this->info->weather);
        $sunValue = $this->playerHandler->GetSunValue($climate->lighting);
        $terrainValue = $this->playerHandler->GetTerrainValue($racePlayer->trackType);
        $windValue = $this->playerHandler->GetWindValue($this->PlayerWindDirection());
        
        $result = 0;
        if($racePlayer->trackShape == SceneValue::Straight){
            
            if($racePlayer->hp > 0){
                $result = $climatAccelerations * 
                ($player->breakOut / ($slope * 5) + $player->velocity / ($slope * 15)) * 
                (($envValue + $climateValue + $sunValue + $terrainValue + $windValue - 400) / 100);
            }else{
                $result = $climatAccelerations * 
                ($player->breakOut + $player->will) / ($slope * 15) * 
                (($envValue + $climateValue + $sunValue + $terrainValue + $windValue - 400) / 100);
            }
            
        }else{
            
            if($racePlayer->hp > 0){
                $result = $climatAccelerations * 
                ($player->velocity / ($slope * 5) + $player->breakOut / ($slope * 15)) * 
                (($envValue + $climateValue + $sunValue + $terrainValue + $windValue - 400) / 100);
            }else{
                $result = $climatAccelerations * 
                ($player->velocity + $player->will) / ($slope * 15) * 
                (($envValue + $climateValue + $sunValue + $terrainValue + $windValue - 400) / 100);
            }
        }
        
        $windEffectValue = $this->WindEffectValue();
        $rhythmValueS = $this->RhythmValueS();
        return ($result + $windEffectValue) * $rhythmValueS + $this->playerHandler->offsetS;
    }
    
    public function ValueH() : float{
        
        $scene = $this->sceneHandler->GetInfo();
        $climate = $this->sceneHandler->GetClimate();
        $player = $this->playerHandler->GetInfo();
        $racePlayer = $this->racePlayerHandler->GetInfo();
        $slope = RaceValue::TrackType[$racePlayer->trackType];

        $climateLoses = RaceValue::ClimateLoses[$this->info->weather];
        $envValue = $this->playerHandler->GetEnvValue($scene->env);
        $climateValue = $this->playerHandler->GetClimateValue($this->info->weather);
        $sunValue = $this->playerHandler->GetSunValue($climate->lighting);
        $terrainValue = $this->playerHandler->GetTerrainValue($racePlayer->trackType);
        $windValue = $this->playerHandler->GetWindValue($this->PlayerWindDirection());
        $valueS = $this->ValueS();
        
        $result = match ($racePlayer->trackType){
            SceneValue::Upslope => $climateLoses + 
                    4 * $slope * $valueS / $player->will * 
                    ( 100 / ($envValue + $climateValue + $sunValue + $terrainValue + $windValue - 400)),
            SceneValue::Downslope => $climateLoses + 
                    4 * $slope * $valueS / $player->intelligent * 
                    ( 100 / ($envValue + $climateValue + $sunValue + $terrainValue + $windValue - 400)),
            default => $climateLoses + 
                    4 * $slope * 2 * $valueS / ($player->intelligent + $player->will) * 
                    ( 100 / ($envValue + $climateValue + $sunValue + $terrainValue + $windValue - 400))
        };
        
        $phythmValueH = $this->RhythmValueH();
        return $result * $phythmValueH + $this->playerHandler->offsetH;
    }

    public function StartSecond() : float{
        $scene = $this->sceneHandler->GetInfo();
        $climate = $this->sceneHandler->GetClimate();
        $player = $this->playerHandler->GetInfo();
        return 0.01 * ( 100 / $player->intelligent + 100 / $this->playerHandler->GetEnvValue($scene->env) + 100 / $this->playerHandler->GetClimateValue($this->info->weather) + 100 / $this->playerHandler->GetSunValue($climate->lighting));
    }
    
    /**
     * 是否發動滿星效果
     * @param SkillHandler $skill
     * @return bool
     */
    public function LaunchMaxEffect(SkillHandler $skill) : bool{
        
        $config = ConfigGenerator::Instance();
        $racePlayerInfo = $this->racePlayerHandler->GetInfo();
        $skillInfo = $skill->GetInfo();
        
        return match ($skillInfo->maxCondition){
            SkillValue::MaxConditionNone => true,
            SkillValue::MaxConditionRank => $racePlayerInfo->ranking == $skillInfo->maxConditionValue,
            SkillValue::MaxConditionTop => $racePlayerInfo->ranking <= $skillInfo->maxConditionValue,
            SkillValue::MaxConditionBotton => $racePlayerInfo->ranking <= $config->AmountRacePlayerMax -  $skillInfo->maxConditionValue,
            SkillValue::MaxConditionOffside => $racePlayerInfo->offside >= $skillInfo->maxConditionValue,
            SkillValue::MaxConditionHit => $racePlayerInfo->hit >= $skillInfo->maxConditionValue,
            SkillValue::MaxConditionStraight => $racePlayerInfo->trackShape == SceneValue::Straight,
            SkillValue::MaxConditionCurved => $racePlayerInfo->trackShape == SceneValue::Curved,
            SkillValue::MaxConditionFlat => $racePlayerInfo->trackType == SceneValue::Flat,
            SkillValue::MaxConditionUpslope => $racePlayerInfo->trackType == SceneValue::Upslope,
            SkillValue::MaxConditionDownslope => $racePlayerInfo->trackType == SceneValue::Downslope,
            SkillValue::MaxConditionTailwind => $this->PlayerWindDirection() == SceneValue::Tailwind,
            SkillValue::MaxConditionHeadwind => $this->PlayerWindDirection() == SceneValue::Headwind,
            SkillValue::MaxConditionCrosswind => $this->PlayerWindDirection() == SceneValue::Crosswind,
            SkillValue::MaxConditionSunny => $this->info->weather == SceneValue::Sunny,
            SkillValue::MaxConditionAurora => $this->info->weather == SceneValue::Aurora,
            SkillValue::MaxConditionSandDust => $this->info->weather == SceneValue::SandDust,
            SkillValue::MaxConditionDune => $this->sceneHandler->GetInfo()->env == SceneValue::Dune,
            SkillValue::MaxConditionCraterLake => $this->sceneHandler->GetInfo()->env == SceneValue::CraterLake,
            SkillValue::MaxConditionVolcano => $this->sceneHandler->GetInfo()->env == SceneValue::Volcano,
            default => false
        };
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
     * 比賽節奏 S 值倍數
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
     * 比賽節奏 H 值倍數
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
