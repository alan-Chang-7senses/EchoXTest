<?php

namespace Games\Leadboards;


class RatingResult 
{
    /**角色編號或使用者編號 */
    public int $userId;
    public int $petaId;
    public string $petaName;
    public int $rate;
    public int $rank;
    public int $playCount;
    public string $itemName;
}