<?php

namespace Games\Pools;

use Accessors\MemcacheAccessor;

class SpecifyPlayerPool extends PlayerPool
{
    private static SpecifyPlayerPool $instance;

    protected string $keyPrefix = self::Key;

    const Key = 'specifyPlayer_';

    public function SpecifyLevel(int $playerID,?int $playerLevel, ?int $skillLevel)
    {
        if(!empty($this->$playerID))unset($this->$playerID);

        $mem = MemcacheAccessor::Instance();
        $data = [];
        $specifyData = $mem->get(self::Key . $playerID);
        if($specifyData !== false)
        {
            $data = json_decode($specifyData,true); 
            $levels = array_column($data,'playerLevel');
            $skillLevels = array_column($data,'skillLevel');
            if(!in_array($playerLevel,$levels) || !in_array($skillLevel,$skillLevels))
            {
                $data[] = ['playerLevel' => $playerLevel, 'skillLevel' => $skillLevel];
                $mem->set(self::Key . $playerID,json_encode($data));
            }
        }
        else
        {
            $data[] = ['playerLevel' => $playerLevel, 'skillLevel' => $skillLevel];
            $mem->set(self::Key . $playerID,json_encode($data));
        }

        $this->playerLevelSpecify = $playerLevel;
        $this->skillLevelSpecify = $skillLevel;
        $this->keyPrefix = self::Key . $playerLevel . '_' . $skillLevel . '_';
    }

    public function Delete($id) : bool
    {
        $mem = MemcacheAccessor::Instance();
        $json = $mem->get(self::Key.$id);
        if($json !== false)
        {
            $dataCollection = json_decode($json,true);
            foreach($dataCollection as $data)
            {
                $key = self::Key.$data['playerLevel'].'_'.$data['skillLevel'].'_'.$id;
                $mem->delete($key);
            }
            $mem->delete(self::Key.$id);
        }
        return !empty($json);
    }

    public static function Instance(): SpecifyPlayerPool
    {
        if(empty(self::$instance))self::$instance = new SpecifyPlayerPool();
        return self::$instance;    
    }
}