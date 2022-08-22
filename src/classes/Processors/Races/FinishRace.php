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
use Generators\DataGenerator;
use Helpers\InputHelper;
use Holders\ResultData;
/**
 * Description of FinishRace
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class FinishRace extends BaseRace{
    
    private array $rewardField = [
        RaceValue::LobbyNone => 'coinReward',
        RaceValue::LobbyCoin => 'coinReward',
        RaceValue::LobbyPT => 'petaTokenReward',
    ];

    public function Process(): ResultData {
        
        $players = json_decode(InputHelper::post('players'));
        $playerCount = count($players);
        if(!is_array($players) || $playerCount <= 0 || $playerCount > ConfigGenerator::Instance()->AmountRacePlayerMax) throw new RaceException(RaceException::IncorrectPlayerNumber);
        DataGenerator::ExistProperties($players[0], ['id', 'ranking']);
        
        $rankings = [];
        foreach($players as $player){
            $rankings[$player->id] = $player->ranking;
        }
        
        $raceID = $this->userInfo->race;
        $racePool = RacePool::Instance();
        $raceInfo = $racePool->$raceID;
        if($raceInfo->status == RaceValue::StatusFinish) throw new RaceException(RaceException::Finished);
        
        $userPool = UserPool::Instance();
        $racePlayerPool = RacePlayerPool::Instance();
        $singleRankingRewardPool = SingleRankingRewardPool::Instance();
        $users = [];
        $rewards = [];
        $items = [];
        foreach ($raceInfo->racePlayers as $racePlayerID) {
            
            $racePlayerInfo = $racePlayerPool->$racePlayerID;
            if($racePlayerInfo->status != RaceValue::StatusReach) throw new RaceException(RaceException::PlayerNotReached, ['[player]' => $racePlayerInfo->player]);
            
            if(!isset($rankings[$racePlayerInfo->player]) || $rankings[$racePlayerInfo->player] != $racePlayerInfo->ranking){
                throw new RaceException(RaceException::RankingNoMatch, [
                    '[player]' => $racePlayerInfo->player,
                    '[front]' => $rankings[$racePlayerInfo->player] ?? 0,
                    '[back]' => $racePlayerInfo->ranking,
                ]);
            }
            
            $rewardID = $singleRankingRewardPool->GetInfo($playerCount, $racePlayerInfo->ranking)->{$this->rewardField[$this->userInfo->lobby]};
            $rewardHandler = new RewardHandler($rewardID);
            $rewards[$racePlayerInfo->user] = $rewardHandler;
            $items[$racePlayerInfo->user] = array_values($rewardHandler->GetItems());
            
            $users[] = [
                'id' => $racePlayerInfo->user,
                'nickname' => $userPool->{$racePlayerInfo->user}->nickname,
                'player' => $racePlayerInfo->player,
                'ranking' => $racePlayerInfo->ranking,
                'duration' => $racePlayerInfo->finishTime - $racePlayerInfo->createTime,
                'items' => array_map(function($value){
                    return [
                        'id' => $value->ItemID,
                        'icon' => ItemInfoPool::Instance()->{$value->ItemID}->Icon,
                        'amount' => $value->Amount,
                    ];
                }, $items[$racePlayerInfo->user]),
            ];
        }
        
        $ticket = RaceUtility::GetTicketID($this->userInfo->lobby);
        if($ticket != RaceValue::NoTicketID) {
            
            foreach($users as $user) {
                if($user['id'] <= 0) continue;
                $userBagHandler = new UserBagHandler($user['id']);
                $userBagHandler->DecItemByItemID($ticket, 1);
            }
        }
        
        foreach ($users as $user) {
            
            if($user['id'] <= 0) continue;
            
            $rewardInfo = $rewards[$user['id']]->GetInfo();
            if($rewardInfo->Modes == RewardValue::ModeSelfSelect) continue;
            
            UserUtility::AddItems($user['id'], $items[$user['id']]);
        }
        
        $accessor = new PDOAccessor(EnvVar::DBMain);
        $accessor->Transaction(function() use ($accessor, $raceID, $users){
            
            $currentTime = $GLOBALS[Globals::TIME_BEGIN];
            
            $accessor->FromTable('RacePlayer')->WhereEqual('RaceID', $raceID)->Modify([
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
        
        foreach($users as $user){
            if($user['id'] <= 0) continue;
            $accessor->CallProcedure('UserRaceTimingRecord', [
                'userID' => $user['id'],
                'duration' => $user['duration'],
                'updateTime' => $GLOBALS[Globals::TIME_BEGIN]
            ]);
        }
        
        $result = new ResultData(ErrorCode::Success);
        $result->users = $users;
        return $result;
    }
}
