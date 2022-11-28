<?php

namespace Games\CommandWorkers;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\Globals;
use Games\Consts\RaceValue;
use Games\Leadboards\LeadboardUtility;
use Games\Mails\MailsHandler;
use Games\Users\RewardHandler;
use Generators\ConfigGenerator;
/**
 * Description of SeasonRankingReward
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class SeasonRankingReward extends BaseWorker{
    
    private array $rewardField = [
        RaceValue::LobbyCoinA => 'CoinReward',
        RaceValue::LobbyPetaTokenA => 'PetaTokenReward',
        RaceValue::LobbyCoinB => 'CoinRewardB',
        RaceValue::LobbyPetaTokenB => 'PetaTokenRewardB',
    ];
    
    public function Process(): array {
        
        echo '== Process Start .. Get SeasonID ..'.PHP_EOL;
        
        $logAccessor = new PDOAccessor(EnvVar::DBLog);
        $logAccessor->FromTable('SeasonRankingReward');
        
        if(!empty($this->SeasonID)){
            
            $row = $logAccessor->WhereEqual('SeasonID', $this->SeasonID)->Fetch();
            if($row === false) $seasonID = $this->SeasonID;
            else return ['Message' => 'Reward has been settled', 'SeasonID' => $this->SeasonID];
            
        }else{
            
            $row = $logAccessor->OrderBy('Serial', 'DESC')->Limit(1)->Fetch();
            $seasonID = $row === false ? 1 : $row->SeasonID + 1;
        }
        
        echo '== Checked SeasonID => '.$seasonID.PHP_EOL;
        
        $mainAccessor = new PDOAccessor(EnvVar::DBMain);
        $row = $mainAccessor->FromTable('QualifyingSeason')->WhereEqual('QualifyingSeasonID', $seasonID)->Fetch();
        if($row === false) return ['Message' => 'No season', 'SeasonID' => $seasonID];
        
        $currentTime = $GLOBALS[Globals::TIME_BEGIN];
        if($row->EndTime > $currentTime) return ['Message' => 'This season not end yet.', 'SeasonID' => $seasonID];
        
        echo '== Has a season must to process..'.PHP_EOL;
        echo '== Get Season Ranking Rewards ..'.PHP_EOL;
        
        $staticAccessor = new PDOAccessor(EnvVar::DBStatic);
        $rows = $staticAccessor->FromTable('SeasonRankingReward')->FetchAll();
        $rewards = [];
        foreach($rows as $row) $rewards[$row->Ranking] = $row;
        
        echo '== Get Season Ranking Rewards comoleted..'.PHP_EOL.PHP_EOL;
        
        $limit = count($rewards);
        $lobbyCoin = $this->RewardAndLog(RaceValue::LobbyCoinA, $seasonID, $limit, $rewards);
        $lobbyPetaToken = $this->RewardAndLog(RaceValue::LobbyPetaTokenA, $seasonID, $limit, $rewards);
        $lobbyCoinB = $this->RewardAndLog(RaceValue::LobbyCoinB, $seasonID, $limit, $rewards);
        $lobbyPetaTokenB = $this->RewardAndLog(RaceValue::LobbyPetaTokenB, $seasonID, $limit, $rewards);
        
        return [
            'SeasonID' => $seasonID,
            'Result' => [
                'Coin' => $lobbyCoin,
                'CoinB' => $lobbyCoinB,
                'PetaToken' => $lobbyPetaToken,
                'PetaTokenB' => $lobbyPetaTokenB,
            ],
        ];
    }
    
    private function RewardAndLog(int $lobby, int $seasonID, int $limit, array $rewards) : int{
        
        echo '== Start Reward And Log.. Lobby => '.$lobby.PHP_EOL;
        
        $content = LeadboardUtility::LeadRateContents[$lobby];
        $table = $content['table'];
        $treshold = ConfigGenerator::Instance()->{$content['tresholdParam']} - 1;
        
        $accessor = new PDOAccessor(EnvVar::DBMain);
        $rows = $accessor->SelectExpr('PlayerID, UserID')
                ->FromTableJoinUsing($table, 'PlayerHolder', 'LEFT', 'PlayerID')
                ->WhereEqual('SeasonID', $seasonID)->WhereGreater('PlayCount', $treshold)
                ->OrderBy('LeadRate', 'DESC')->OrderBy('PlayCount', 'DESC')->OrderBy('UpdateTime')
                ->Limit($limit)->FetchAll();
        
        echo '== Count => '.count($rows).PHP_EOL;
        
        $config = ConfigGenerator::Instance();
        $mailsHandler = new MailsHandler();
        
        $logAccessor = new PDOAccessor(EnvVar::DBLog);
        $logAccessor->FromTable('SeasonRankingReward');
        
        $ranking = 1;
        $rewardField = $this->rewardField[$lobby];
        foreach($rows as $row){
            
            echo '== Ranking => '.$ranking.PHP_EOL;
            
            $rewardID = $rewards[$ranking]->$rewardField;
            if(empty($rewardID)){
                
                echo '== RewardID is empty.. RewardField => '.$rewardField.PHP_EOL;
                continue;
            }
            
            $rewardHandler = new RewardHandler($rewardID);
            $items = array_values($rewardHandler->GetItems());
            
            $userMailID = $mailsHandler->AddMail($row->UserID, $config->SeasonRankingRewardMailID, $config->SeasonRankingRewardMailDay);
            $mailsHandler->AddMailItems($userMailID, $items);
            
            echo '== Add MailItems completed.. UserMailID => '.$userMailID.' Items => '. json_encode($items).PHP_EOL;
            
            $content = array_map(function($val){
                return [
                    'ItemID' => $val->ItemID,
                    'Amount' => $val->Amount
                ];
            }, $items);
            
            $logContent = [
                'SeasonID' => $seasonID,
                'Lobby' => $lobby,
                'Ranking' => $ranking,
                'UserID' => $row->UserID,
                'PlayerID' => $row->PlayerID,
                'Content' => json_encode($content),
                'LogTime' => $GLOBALS[Globals::TIME_BEGIN],
            ];
            
            $logAccessor->Add($logContent);
            
            echo '== Add Log completed.. content => '. json_encode($logContent).PHP_EOL;
            
            ++$ranking;
        }
        
        echo '== End Reward And Log.. End Ranking => '. $ranking.PHP_EOL.PHP_EOL;
        
        return $ranking - 1;
    }
}
