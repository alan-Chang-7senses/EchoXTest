<?php

namespace Processors\Leaderboard;

use Consts\ErrorCode;
use Games\Accessors\AccessorFactory;
use Games\Exceptions\LeaderboardException;
use Holders\ResultData;
use Processors\BaseProcessor;

class LeaderBoardRatingInfo extends BaseProcessor
{
    public function Process(): ResultData
    {
        $staticAccessor = AccessorFactory::Static();
        $rows = $staticAccessor->FromTable('Leaderboard')->FetchAll();
        if($rows === false)throw new LeaderboardException(LeaderboardException::NoAnyLeaderboardData);

        $result = new ResultData(ErrorCode::Success);

        $result->leaderBoards = [];
        foreach($rows as $row)
        {
            $seasonID = $row->SeasonID;
            $staticAccessor->ClearCondition()
                           ->FromTable()

            $result->leaderBoards[] = 
            [
                'id' => $row->Serial,
                'group' => $row->Group,
                'mainLeaderboardTitle' => $row->MainLeaderboradName,
                'subLeaderboardTitle' => $row->SubLeaderboradName,
                'ruleHint' => $row->CompetitionRuleHint,
                'reward' => 
            ];
        }
        return $result;
    }
}