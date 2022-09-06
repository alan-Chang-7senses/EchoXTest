<?php

namespace Processors\Races;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\Globals;
use Games\Consts\RaceValue;
use Games\Consts\RewardValue;
use Games\Exceptions\RaceException;
use Games\Pools\ItemInfoPool;
use Games\Pools\RacePlayerPool;
use Games\Pools\RacePool;
use Games\Pools\SingleRankingRewardPool;
use Games\Pools\UserPool;
use Games\Races\RaceUtility;
use Games\Users\RewardHandler;
use Games\Users\UserBagHandler;
use Games\Users\UserUtility;
use Generators\ConfigGenerator;
use Holders\ResultData;
/**
 * Description of FinishRace
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class FinishRace extends BaseRace{
    
    private array $rewardField = [
        RaceValue::LobbyCoin => 'coinReward',
        RaceValue::LobbyPT => 'petaTokenReward',
    ];
    
    private array $leaderboardLeadFunc = [
        RaceValue::LobbyCoin => 'LeaderboardLeadCoin',
        RaceValue::LobbyPT => 'LeaderboardLeadPT',
    ];

    public function Process(): ResultData {
        
        $raceID = $this->userInfo->race;
        $racePool = RacePool::Instance();
        $raceInfo = $racePool->$raceID;
        if($raceInfo->status == RaceValue::StatusFinish) throw new RaceException(RaceException::Finished);
        
        $racePlayersArr = (array)$raceInfo->racePlayers;
        $playerCount = count($racePlayersArr);
        if($playerCount < RaceValue::RacePlayerMin) throw new RaceException(RaceException::IncorrectPlayerNumber);
        
        $accessor = new PDOAccessor(EnvVar::DBMain);
        $accessor->Transaction(function() use ($accessor, $raceInfo){
            
            $racePlayerPool = RacePlayerPool::Instance();
            
            $rows = $accessor->FromTable('RacePlayer')->WhereIn('RacePlayerID', array_values((array)$raceInfo->racePlayers))
                    ->ForUpdate()->FetchAll();
            
            $rankings = [];
            foreach($rows as $row) $rankings[$row->RacePlayerID] = $row->Ranking;
            asort($rankings);
            $n = 0;
            $accessor->PrepareName('RacePlayerRanking');
            foreach(array_keys($rankings) as $id){
                $rankings[$id] = ++$n;
                $accessor->ClearCondition()->WhereEqual('RacePlayerID', $id)->Modify(['Ranking' => $rankings[$id]]);
                $racePlayerPool->Delete($id);
            }
        });
        
        $userPool = UserPool::Instance();
        $racePlayerPool = RacePlayerPool::Instance();
        $singleRankingRewardPool = SingleRankingRewardPool::Instance();
        $users = [];
        $rewards = [];
        $items = [];
        $rewardMultiplier = ConfigGenerator::Instance()->RaceRewardMultiplier ?? 1;
        foreach ($raceInfo->racePlayers as $racePlayerID) {
            
            $racePlayerInfo = $racePlayerPool->$racePlayerID;
            if($racePlayerInfo->status != RaceValue::StatusReach) throw new RaceException(RaceException::PlayerNotReached, ['[player]' => $racePlayerInfo->player]);
            if($racePlayerInfo->ranking > $playerCount) throw new RaceException (RaceException::RankingNoMatch, [
                '[player]' => $racePlayerInfo->player,
                '[ranking]' => $racePlayerInfo->ranking,
                '[count]' => $playerCount,
            ]);
            
            if(isset($this->rewardField[$this->userInfo->lobby] )){
                $rewardID = $singleRankingRewardPool->GetInfo($playerCount, $racePlayerInfo->ranking)->{$this->rewardField[$this->userInfo->lobby]};
                $rewardHandler = new RewardHandler($rewardID);
                $rewards[$racePlayerInfo->user] = $rewardHandler;
                $items[$racePlayerInfo->user] = array_values($rewardHandler->GetItems());
            }else $items[$racePlayerInfo->user] = [];
            
            $users[] = [
                'id' => $racePlayerInfo->user,
                'nickname' => $userPool->{$racePlayerInfo->user}->nickname,
                'player' => $racePlayerInfo->player,
                'ranking' => $racePlayerInfo->ranking,
                'duration' => $racePlayerInfo->finishTime - $racePlayerInfo->createTime,
                'items' => array_map(function($value) use ($rewardMultiplier) {
                    return [
                        'id' => $value->ItemID,
                        'icon' => ItemInfoPool::Instance()->{$value->ItemID}->Icon,
                        'amount' => $value->Amount * $rewardMultiplier,
                    ];
                }, $items[$racePlayerInfo->user]),
            ];
        }
        
        $ticket = RaceUtility::GetTicketID($this->userInfo->lobby);
        if($ticket != RaceValue::NoTicketID) {
            
            foreach($users as $user) {
                if(UserUtility::IsNonUser($user['id'])) continue;
                $userBagHandler = new UserBagHandler($user['id']);
                $userBagHandler->DecItemByItemID($ticket, 1);
            }
        }
        
        if(isset($this->leaderboardLeadFunc[$this->userInfo->lobby])) $leadRates = $this->{$this->leaderboardLeadFunc[$this->userInfo->lobby]}();
        
        foreach ($users as $idx => $user) {
            
            if(UserUtility::IsNonUser($user['id'])) continue;
            
            $users[$idx]['leadRate'] = $leadRates[$user['player']] ?? 0;
            
            if(empty($rewards[$user['id']])) continue;
            
            $rewardInfo = $rewards[$user['id']]->GetInfo();
            if($rewardInfo->Modes == RewardValue::ModeSelfSelect) continue;
            
            UserUtility::AddItems($user['id'], $items[$user['id']]);
        }
        
        $accessor->Transaction(function() use ($accessor, $raceID, $racePlayersArr, $users){
            
            $currentTime = $GLOBALS[Globals::TIME_BEGIN];
            
            $accessor->ClearAll()
                    ->FromTable('RacePlayer')->WhereIn('RacePlayerID', array_values($racePlayersArr))
                    ->Modify([
                        'Status' => RaceValue::StatusFinish,
                        'UpdateTime' => $currentTime,
                    ]);
            
            $accessor->ClearCondition()
                    ->FromTable('Users')->WhereIn('UserID', array_column($users, 'id'))
                    ->Modify([
                        'Race' => RaceValue::NotInRace,
                        'Lobby' => RaceValue::NotInLobby,
                        'Room' => RaceValue::NotInRoom,
                        'UpdateTime' => $currentTime,
                    ]);
            
            $accessor->ClearCondition()
                    ->FromTable('Races')->WhereEqual('RaceID', $raceID)
                    ->Modify([
                        'Status' => RaceValue::StatusFinish,
                        'UpdateTime' => $currentTime,
                        'FinishTime' => $currentTime,
                    ]);
        });
        
        foreach ($users as $user) $userPool->Delete($user['id']);
        foreach($raceInfo->racePlayers as $racePlayerID) $racePlayerPool->Delete($racePlayerID);
        $racePool->Delete($raceID);
        
        $currentTime = $GLOBALS[Globals::TIME_BEGIN];
        foreach($users as $user){    
            if(UserUtility::IsNonUser($user['id'])) continue;
            $accessor->CallProcedure('UserRaceTimingRecord', [
                'userID' => $user['id'],
                'duration' => $user['duration'],
                'updateTime' => $currentTime,
            ]);
        }
        
        $whereValues = $accessor->valuesForWhereIn(array_column($users, 'id'));
        $whereValues->bind['updateTime'] = $currentTime;
        $accessor->executeBind('UPDATE `TotalUserRace` SET `FinishAmount` = `FinishAmount` + 1, UpdateTime = :updateTime WHERE UserID IN '.$whereValues->values, $whereValues->bind);
        
        $result = new ResultData(ErrorCode::Success);
        $result->users = $users;
        return $result;
    }
    
    private function LeaderboardLeadCoin() : array{
        return $this->RecordLeaderboardLead('LeaderboardLeadCoin', 'Games\Races\RaceUtility::QualifyingSeasonID');
    }
    
    private function LeaderboardLeadPT() : array{
        return $this->RecordLeaderboardLead('LeaderboardLeadPT', 'Games\Races\RaceUtility::QualifyingSeasonID');
    }
    
    private function RecordLeaderboardLead(string $table, $seasonIDFunc) : array{
        
        $racePlayerPool = RacePlayerPool::Instance();
        $racePlayers = (array)RacePool::Instance()->{$this->userInfo->race}->racePlayers;
        $leadRanking = RaceValue::LeadRanking[count($racePlayers)];
        
        $accessor = new PDOAccessor(EnvVar::DBMain);
        $seasonID = call_user_func($seasonIDFunc);
        $rows = $accessor->FromTable($table)
                ->WhereEqual('SeasonID', $seasonID)->WhereIn('PLayerID', array_keys($racePlayers))
                ->FetchAll();
        
        $data = [];
        foreach($rows as $row) $data[$row->PlayerID] = $row;
        
        $insert = [];
        $leadRates = [];
        $currentTime = $GLOBALS[Globals::TIME_BEGIN];
        foreach($racePlayers as $playerID => $racePlayerID){
            $racePlayerInfo = $racePlayerPool->$racePlayerID;
            if(isset($data[$playerID])){
                $leadCount = $racePlayerInfo->ranking <= $leadRanking ? $data[$playerID]->LeadCount + 1 : $data[$playerID]->LeadCount ;
                $playCount = $data[$playerID]->PlayCount + 1;
                $leadRate = RaceUtility::GetLeadRateForWriteDB($leadCount, $playCount);
                $accessor->ClearCondition();
                $accessor->FromTable($table)->WhereEqual('Serial', $data[$playerID]->Serial)->Modify([
                    'LeadCount' => $leadCount,
                    'PlayCount' => $playCount,
                    'LeadRate' => $leadRate,
                    'UpdateTime' => $currentTime,
                ]);
            }else{
                $leadCount = $racePlayerInfo->ranking <= $leadRanking ? 1 : 0;
                $playCount = 1;
                $leadRate = RaceUtility::GetLeadRateForWriteDB($leadCount, $playCount);
                $insert[] = [
                    'SeasonID' => $seasonID,
                    'PlayerId' => $playerID,
                    'LeadCount' => $leadCount,
                    'PlayCount' => $playCount,
                    'LeadRate' => $leadRate,
                    'UpdateTime' => $currentTime,
                ];
            }
            $leadRates[$playerID] = $leadRate / RaceValue::DivisorPercent;
        }
        
        if(!empty($insert)) $accessor->FromTable($table)->AddAll($insert);
        
        return $leadRates;
    }
}
