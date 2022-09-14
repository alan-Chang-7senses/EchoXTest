<?php

namespace Games\Races\Holders;

use stdClass;

/*
 * Description of RaceVerifyHolder
 */

class RaceVerifyHolder extends stdClass {

    /** @var int 競賽角色編號 RacePlayerID */
    public int $racePlayerID;

    /** @var int 驗證階段 VerifyStage */
    public int $verifyStage;

    /** @var float 當前速度 Speed */
    public float $speed;

    /** @var float 移動距離 ServerDistance */
    public float $serverDistance;
       
    /** @var float 誤差值 ClientDistance */
    public float $clientDistance;

    /** @var int 是否作弊 IsCheat */
    //public int $isCheat;

    /** @var float 更新時間 UpdateTime */
    public float $updateTime;

    /** @var int 開始時間 StartTime */
    public int $startTime;
   
    /** @var int 建立時間 CreateTime */
    public int $createTime;

}
