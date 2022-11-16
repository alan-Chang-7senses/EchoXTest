<?php

namespace Games\Pools;

use Accessors\MemcacheAccessor;

class SpecifyPlayerPool extends PlayerPool
{
    private static SpecifyPlayerPool $instance;

    protected string $keyPrefix = self::Key;

    private const Key = 'specifyPlayer_';

    public function SpecifyLevel(int $playerID,?int $playerLevel, ?int $skillLevel)
    {
        if(!empty($this->$playerID))unset($this->$playerID);
        $this->playerLevelSpecify = $playerLevel;
        $this->skillLevelSpecify = $skillLevel;

        $this->keyPrefix = self::Key . $playerLevel . '_' . $skillLevel . '_';
    }

    public static function Instance(): SpecifyPlayerPool
    {
        if(empty(self::$instance))self::$instance = new SpecifyPlayerPool();
        return self::$instance;    
    }

    public function Delete(string $id) : bool{
        $this->keyPrefix = self::Key;

        $key = $this->keyPrefix. $this->playerLevelSpecify. '_'. $this->skillLevelSpecify. '_'. $id;
        $res = MemcacheAccessor::Instance()->delete($key);
        if($res) unset ($this->$id);
        return $res;
    }
}