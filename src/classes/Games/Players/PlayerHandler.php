<?php

namespace Games\Players;

use Games\Consts\AbilityFactor;
use Games\Consts\SceneValue;
use Games\Consts\SkillValue;
use Games\Exceptions\PlayerException;
use Games\Players\Exp\ExpBonus;
use Games\Players\Exp\ExpBonusCalculator;
use Games\Players\Exp\PlayerEXP;
use Games\Players\Holders\PlayerInfoHolder;
use Games\Pools\PlayerPool;
use stdClass;
/**
 * Description of PlayerHandler
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class PlayerHandler {
    
    private PlayerPool $pool;
    private PlayerInfoHolder|stdClass $info;
    
    public float $offsetS = 0;
    public float $offsetH = 0;
    
    public float $offsetDune = 0;
    public float $offsetCraterLake = 0;
    public float $offsetVolcano = 0;
    
    public float $offsetTailwind = 0;
    public float $offsetHeadwind = 0;
    public float $offsetCrosswind = 0;
    
    public float $offsetSunny = 0;
    public float $offsetAurora = 0;
    public float $offsetSandDust = 0;
    
    public float $offsetFlat = 0;
    public float $offsetUpslope = 0;
    public float $offsetDownslope = 0;
    
    public float $offsetSun = 0;

    private array $skills = [];
    private array $skillIDs = [];

    public function __construct(int|string $id) {
        $this->pool = PlayerPool::Instance();
        $info = $this->pool->$id;
        if($info === false) throw new PlayerException(PlayerException::PlayerNotExist, ['[player]' => $id]);
        $this->info = $info;
        foreach($this->info->skills as $skill){
            $this->skills[$skill->id] = $skill;
            $this->skillIDs[] = $skill->id;
        }
    }
    
    public function GetInfo() : PlayerInfoHolder|stdClass{
        return $this->info;
    }
    
    /**
     * 取得環境適性值
     * @param int $env 場景環境
     * @return float
     */
    public function GetEnvValue(int $env) : float{
        return match ($env) {
            SceneValue::Dune => PlayerUtility::AdaptValueByPoint($this->info->dune) + $this->offsetDune,
            SceneValue::CraterLake => PlayerUtility::AdaptValueByPoint($this->info->craterLake) + $this->offsetCraterLake,
            SceneValue::Volcano => PlayerUtility::AdaptValueByPoint($this->info->volcano) + $this->offsetVolcano,
            default => 0,
        };
    }
    
    /**
     * 取得氣候適性值
     * @param int $weather
     * @return float
     */
    public function GetClimateValue(int $weather) : float{
        return match ($weather) {
            SceneValue::Sunny => PlayerUtility::AdaptValueByPoint($this->info->sunny) + $this->offsetSunny,
            SceneValue::Aurora => PlayerUtility::AdaptValueByPoint($this->info->aurora) + $this->offsetAurora,
            SceneValue::SandDust => PlayerUtility::AdaptValueByPoint($this->info->sandDust) + $this->offsetSandDust,
            default => 0,
        };
    }

    /**
     * 取得太陽適性值
     * @param int $lighting 場景照明
     * @return float
     */
    public function GetSunValue(int $lighting) : float{
        return PlayerUtility::SunValueByLighting($this->info->sun, $lighting) + $this->offsetSun;
    }

    /**
     * 取得地形適性值
     * @param int $trackType 賽道地形
     * @return float
     */
    public function GetTerrainValue(int $trackType) : float{
        return match ($trackType) {
            SceneValue::Flat => PlayerUtility::AdaptValueByPoint($this->info->flat) + $this->offsetFlat,
            SceneValue::Upslope => PlayerUtility::AdaptValueByPoint($this->info->upslope) + $this->offsetUpslope,
            SceneValue::Downslope => PlayerUtility::AdaptValueByPoint($this->info->downslope) + $this->offsetDownslope,
            default => 0,
        };
    }
    
    /**
     * 取得風勢適性值
     * @param int $playerWindDirection 角色對應場景風向
     * @return int
     */
    public function GetWindValue(int $playerWindDirection) : int{
        return match ($playerWindDirection) {
            SceneValue::Tailwind => PlayerUtility::AdaptValueByPoint($this->info->tailwind) + $this->offsetTailwind,
            SceneValue::Crosswind => PlayerUtility::AdaptValueByPoint($this->info->crosswind) + $this->offsetCrosswind,
            SceneValue::Headwind => PlayerUtility::AdaptValueByPoint($this->info->headwind) + $this->offsetHeadwind,
            default => 0,
        };
    }
    
    public function HasSkill(int $id) : bool{
        return in_array($id, $this->skillIDs);
    }
    
    public function SkillLevel(int $id) : int{
        if(!isset($this->skills[$id])) return SkillValue::LevelMin;
        return $this->skills[$id]->level;
    }

    
    private function ResetInfo() : void{
        $this->info = $this->pool->{$this->info->id};
    }

    public function SaveData(array $bind) : void{    
        $this->pool->Save($this->info->id, 'Data', $bind);
        $this->ResetInfo();
    }

    /**
     * @param int $rawExp
     * @param ExpBonus $bonuses 效果集合，沒有加成可以不用給。
     */
    public function GainExp(int|float $rawExp, ...$bonuses) : stdClass
    {
        $expCalculator = new ExpBonusCalculator($rawExp);                
        foreach($bonuses as $bonus)
        {
            $expCalculator->AddBonus($bonus);
        }
        $rt = $expCalculator->Process();
        $this->UpdateExp($rt->exp);
        return $rt;
    }

    private function UpdateExp(int $exp)
    {
        $currentExp = $this->info->exp;
        //限制不超過目前等階最大等級之經驗值
        $currentExpTemp = PlayerEXP::IsLevelMax($currentExp + $exp,$this->info->rank) ? 
            PlayerEXP::GetMaxEXP($this->info->rank) :
             $currentExp + $exp;             
        $level = PlayerEXP::GetLevel($currentExpTemp,$this->info->rank,$this->info->level);
        $bind = [];
        $bind['exp'] = $currentExpTemp;
        if($level != $this->info->level)
        {
            $bind['level'] = $level;
        }
        $this->SaveData($bind);
    }
}
