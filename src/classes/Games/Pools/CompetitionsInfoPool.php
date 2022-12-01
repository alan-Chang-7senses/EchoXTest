<?php

namespace Games\Pools;

use Accessors\PoolAccessor;
use Games\Accessors\AccessorFactory;
use Games\Consts\RaceValue;
use Games\Exceptions\RaceException;
use Games\PVP\Holders\CompetitionsInfoHolder;
use stdClass;

class CompetitionsInfoPool extends PoolAccessor
{
    const NoValueLobby = 
    [
        RaceValue::LobbyStudy,
        RaceValue::LobbyPVE,
    ];
    private static CompetitionsInfoPool $instance;
    public static function Instance() : CompetitionsInfoPool
    {
        if(empty(self::$instance))self::$instance = new CompetitionsInfoPool();
        return self::$instance;
    }
    protected string $keyPrefix = 'CompetitionsInfo_';

    public function FromDB(int|string $id): CompetitionsInfoHolder|stdClass|false
    {
        if(in_array($id,self::NoValueLobby))throw new RaceException(RaceException::NoSeasonData);
        $holder = new CompetitionsInfoHolder();
        $row = AccessorFactory::Static()
            ->FromTable('CompetitionsInfo')
            ->WhereEqual('ID',$id)
            ->Fetch();
        if($row === false)return false;    
        $holder->lobby = $row->ID;
        $holder->minRatingReset = $row->MinRatingReset;
        $holder->resetRate = $row->ResetRate;
        $holder->matchingRange = $row->MatchingRange;
        $holder->newRoomRate = $row->NewRoomRate;
        $holder->maxMatchSecond = $row->MaxMatchSecond;
        $holder->extraMatchSecond = $row->ExtraMatchSecond;
        $holder->minMatchPlayers = $row->MinMatchPlayers;
        $holder->baseRating = $row->BaseRating;
        $holder->minRating = $row->MinRating;
        $holder->maxRating = $row->MaxRating;
        $holder->score1 = $row->Score_1;
        $holder->score2 = $row->Score_2;
        $holder->score3 = $row->Score_3;
        $holder->score4 = $row->Score_4;
        $holder->score5 = $row->Score_5;
        $holder->score6 = $row->Score_6;
        $holder->score7 = $row->Score_7;
        $holder->score8 = $row->Score_8;
        $holder->xValue = $row->XValue;
        $holder->yValue = $row->YValue;
        $holder->kValue = $row->KValue;
        $holder->delta = $row->Delta;
        $holder->bot = $row->BOT;
        $holder->ticketId = $row->TicketId;
        $holder->ticketCost = $row->TicketCost;
        $holder->treshold = $row->Treshold;
        return $holder;
    }
}