<?php

namespace Games\Players;

use Games\Consts\PlayerValue;
use Games\Consts\SceneValue;
use Games\Exceptions\PlayerException;
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

    private array $skillIDs = [];

    public function __construct(int|string $id) {
        $this->pool = PlayerPool::Instance();
        $info = $this->pool->$id;
        if($info === false) throw new PlayerException(PlayerException::PlayerNotExist, ['[player]' => $id]);
        $this->info = $info;
        foreach($this->info->skills as $skill) $this->skillIDs[] = $skill->id;
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
            SceneValue::Sunny => PlayerUtility::AdaptValueByPoint($this->info->sunny),
            SceneValue::Aurora => PlayerUtility::AdaptValueByPoint($this->info->aurora),
            SceneValue::SandDust => PlayerUtility::AdaptValueByPoint($this->info->sandDust),
            default => 0,
        };
    }

    /**
     * 取得太陽適性值
     * @param int $lighting 場景照明
     * @return float
     */
    public function GetSunValue(int $lighting) : float{
        return match ($this->info->sun) {
            SceneValue::SunNone => PlayerValue::SunNone,
            $lighting => PlayerValue::SunSame,
            default => PlayerValue::SunDiff,
        };
    }

    /**
     * 取得地形適性值
     * @param int $trackType 賽道地形
     * @return float
     */
    public function GetTerrainValue(int $trackType) : float{
        return match ($trackType) {
            SceneValue::Flat => PlayerUtility::AdaptValueByPoint($this->info->flat),
            SceneValue::Upslope => PlayerUtility::AdaptValueByPoint($this->info->upslope),
            SceneValue::Downslope => PlayerUtility::AdaptValueByPoint($this->info->downslope),
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
}
