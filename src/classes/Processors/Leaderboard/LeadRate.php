<?php

namespace Processors\Leaderboard;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\Sessions;
use Games\Consts\RaceValue;
use Games\Exceptions\LeaderboardException;
use Games\Leadboards\LeadboardUtility;
use Games\Players\PlayerUtility;
use Games\Pools\UserPool;
use Generators\ConfigGenerator;
use Helpers\InputHelper;
use Holders\ResultData;
/**
 * Description of LeadRate
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class LeadRate extends BaseGameLeaderboard {
    
    public function Process(): ResultData {

        $lobby = InputHelper::post('lobby');
        
        if(!isset(LeadboardUtility::LeadRateContents[$lobby])) throw new LeaderboardException(LeaderboardException::NoLeaderboard);
        $content = LeadboardUtility::LeadRateContents[$lobby];
        
        $table = $content['table'];
        $treshold = ConfigGenerator::Instance()->{$content['tresholdParam']} - 1;
        $seasonID = $content['seasonIdFunc']();
        $accessor = new PDOAccessor(EnvVar::DBMain);
        
        $rows = $accessor->SelectExpr('PlayerID, PlayCount, LeadRate, Nickname, ItemName')
                ->FromTableJoinUsing($table, 'PlayerHolder', 'LEFT', 'PlayerID')
                ->FromTableJoinUsingNext('PlayerNFT', 'LEFT', 'PlayerID')
                ->WhereEqual('SeasonID', $seasonID)->WhereGreater('PlayCount', $treshold)
                ->OrderBy('LeadRate', 'DESC')->OrderBy('PlayCount', 'DESC')->OrderBy('UpdateTime')
                ->Limit($this->length, $this->offset)->FetchAll();
        
        $list = [];
        $ranking = $this->offset + 1;
        foreach($rows as $row){
            $idName = PlayerUtility::GetIDName($row->PlayerID);
            $list[] = [
                'ranking' => $ranking,
                'nickname' => (string)($row->Nickname ?? $idName),
                'itemName' => (string)($row->ItemName ?? $idName),
                'leadRate' => $row->LeadRate / RaceValue::DivisorPercent,
                'playCount' => $row->PlayCount,
            ];
            
            ++$ranking;
        }
        
        $self = LeadboardUtility::PlayerLeadRanking($lobby, UserPool::Instance()->{$_SESSION[Sessions::UserID]}->player, $seasonID);
        
        $result = new ResultData(ErrorCode::Success);
        $result->list = $list;
        $result->self = $self;
        
        return $result;
    }
}
