<?php

namespace Games\FreePlayer;

use Games\Players\Holders\PlayerInfoHolder;
use Games\Players\PlayerHandler;
use Games\Pools\FreePlayerPool;
use Games\Skills\SkillHandler;
use stdClass;

class FreePlayerHandler extends PlayerHandler
{
    private FreePlayerPool $pool;
    private PlayerInfoHolder|stdClass $info;
    
    
     /**
     * @param int|string $number 可指定免費角色類型 1~3。
     */
    public function __construct(int|string $number)
    {
        $this->pool = FreePlayerPool::Instance();
        $info = $this->pool->$number;
        $this->info = $info;
    }
    public function GetInfo(): PlayerInfoHolder|stdClass
    {
        return $this->info;
    }

    public function HandleFreePlayerData() : FreePlayerInfo
    {
        $rt = new FreePlayerInfo();
        $rt->number = $this->info->ele;
        $rt->type = $this->info->type;
        $rt->ele = $this->info->ele;
        $rt->velocity = $this->info->velocity;
        $rt->stamina = $this->info->stamina;
        $rt->breakOut = $this->info->breakOut;
        $rt->will = $this->info->will;
        $rt->intelligent = $this->info->intelligent;
        $rt->dna = $this->info->dna;
        $rt->baseInfo = $this->info->freePlayerBase;
        foreach($this->info->skills as $skill)
        {
            $handler = new SkillHandler($skill->id);
            $handler->playerHandler = $this;
            $skillInfo = $handler->GetInfo();                
            $rt->skills[] = 
            [
                "id" => $skillInfo->id,
                "name" => $skillInfo->name,
                "icon" => $skillInfo->icon,
                "description" => $skillInfo->description,
                "energy" => $skillInfo->energy,
                "cooldowm" => $skillInfo->cooldown,
                "duration" => $skillInfo->duration,
                "ranks" => $skillInfo->ranks,
                "maxDescription" => $skillInfo->maxDescription,
                "maxCondition" => $skillInfo->maxCondition,
                "maxConditionValue" => $skillInfo->maxConditionValue,
                "effects" => $handler->GetEffects(),
                "maxEffects" => $handler->GetMaxEffects(),
            ];
            
        }
        return $rt;
    }


}
