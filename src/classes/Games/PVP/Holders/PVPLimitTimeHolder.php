<?php
namespace Games\PVP\Holders;

class PVPLimitTimeHolder
{
    /** 距離最近比賽開始時間點的剩餘秒數(秒) for client */
    public int $startRemainSeconds;

    /** 距離最近比賽結束時間點的剩餘秒數(秒) for client */
    public int $endRemainSeconds;
}