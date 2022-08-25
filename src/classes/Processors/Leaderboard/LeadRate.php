<?php

namespace Processors\Leaderboard;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\Sessions;
use Games\Consts\RaceValue;
use Games\Exceptions\LeaderboardException;
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
    
    protected array $contents = [
        RaceValue::LobbyCoin => [
            'table' => 'LeaderboardLeadCoin',
            'seasonIdFunc' => 'QualifyingSeasonID',
            'tresholdParam' => 'PvP_B_Treshold_1',
        ],
        RaceValue::LobbyPT => [
            'table' => 'LeaderboardLeadPT',
            'seasonIdFunc' => 'QualifyingSeasonID',
            'tresholdParam' => 'PvP_B_Treshold_2',
        ]
    ];

    protected function QualifyingSeasonID() : int{
        $accessor = new PDOAccessor(EnvVar::DBMain);
        return $accessor->FromTable('QualifyingSeason')->OrderBy('QualifyingSeasonID', 'DESC')->Limit(1)->Fetch()->QualifyingSeasonID;
    }

    public function Process(): ResultData {

        $lobby = InputHelper::post('lobby');
        
        if(!isset($this->contents[$lobby])) throw new LeaderboardException(LeaderboardException::NoLeaderboard);
        $content = $this->contents[$lobby];
        
        $table = $content['table'];
        $treshold = ConfigGenerator::Instance()->{$content['tresholdParam']} - 1;
        $seasonID = $this->{$content['seasonIdFunc']}();
        $accessor = new PDOAccessor(EnvVar::DBMain);
        
        $rows = $accessor->SelectExpr('PlayerID, LeadRate, Nickname, TokenName')
                ->FromTableJoinUsing($table, 'PlayerHolder', 'LEFT', 'PlayerID')
                ->FromTableJoinUsingNext('PlayerNFT', 'LEFT', 'PlayerID')
                ->WhereEqual('SeasonID', $seasonID)->WhereGreater('PlayCount', $treshold)
                ->OrderBy('LeadRate', 'DESC')->OrderBy('PlayCount', 'DESC')->OrderBy('UpdateTime')
                ->Limit($this->length, $this->offset)->FetchAll();
        
        $list = [];
        $ranking = $this->offset + 1;
        foreach($rows as $row){
            
            $list[] = [
                'ranking' => $ranking,
                'nickname' => $row->Nickname ?? $row->PlayerID,
                'tokenName' => $row->TokenName ?? $row->PlayerID,
                'leadRate' => $row->LeadRate / RaceValue::DivisorPercent,
            ];
            
            ++$ranking;
        }
        
        $userInfo = UserPool::Instance()->{$_SESSION[Sessions::UserID]};
        
        $accessor->ClearAll();
        $rowSelf = $accessor->FromTable($table)
                ->WhereEqual('SeasonID', $seasonID)->WhereEqual('PlayerID', $userInfo->player)->WhereGreater('PlayCount', $treshold)
                ->Fetch();
        
        if($rowSelf === false) $ranking = 0;
        else{
            
            $accessor->ClearAll();
            $rows = $accessor->WhereEqual('SeasonID', $seasonID)->WhereEqual('LeadRate', $rowSelf->LeadRate)->FetchAll();

            $accessor->ClearAll();
            $ranking = $accessor->SelectExpr('COUNT(*) AS cnt')
                    ->WhereEqual('SeasonID', $seasonID)->WhereGreater('LeadRate', $rowSelf->LeadRate)->WhereGreater('PlayCount', $treshold)
                    ->Fetch()->cnt;

            foreach($rows as $row){

                if($row->PlayerID == $userInfo->player) continue;
                if($row->PlayCount > $rowSelf->PlayCount){
                    ++$ranking;
                    continue;
                }else if($row->PlayCount == $rowSelf->PlayCount && $row->UpdateTime < $rowSelf->UpdateTime) ++$ranking;
            }
            
            ++$ranking;
        }
        
        $result = new ResultData(ErrorCode::Success);
        $result->list = $list;
        $result->ranking = $ranking;
        
        return $result;
    }
}
