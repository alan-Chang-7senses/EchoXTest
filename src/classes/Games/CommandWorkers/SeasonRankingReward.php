<?php

namespace Games\CommandWorkers;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\Globals;
use Games\Accessors\AccessorFactory;
use Games\Consts\SeasonRewardValue;
use Games\Leadboards\LeadboardUtility;
use Games\Mails\MailsHandler;
use Games\Users\RewardHandler;
use Generators\ConfigGenerator;

use Exception;
use stdClass;

/**
 * Description of SeasonRankingReward
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class SeasonRankingReward extends BaseWorker{

    const LeadRateFunc = [
        'Games\Leadboards\LeadboardUtility::GetSeasonRankingForPlayer',
        'Games\Leadboards\LeadboardUtility::GetSeasonRankingForUser',
    ];
    
    public function Process(): array {

        $season = $this->GetSeasonData();
        if($season == false)
        {
            $season = ["No Season Data"];
        }
        else
        {
            foreach($season as $data)
            {
                $data->result = $this->Award($data->id, $data->lobby, $data->recodeType);
            }
        }
        
        return $season;
    }

    // 取得配獎賽季
    private function GetSeasonData() : array|false {

        // 取得結束的賽季資料
        $Qualifying = AccessorFactory::Static()->SelectExpr('SeasonID, RecordType')
                                        ->FromTableJoinUsing('QualifyingData', 'Leaderboard', 'LEFT', 'SeasonID')
                                        ->WhereLess('EndTime', $GLOBALS[Globals::TIME_BEGIN])
                                        ->FetchAll();

        if (empty($Qualifying))
        {
            return false;
        }

        // 建立計分規則表
        $rules = [];       
        $seasonIDs = [];
        foreach($Qualifying as $item)
        {
            // 防呆處理 將 null 過濾
            if(is_null($item->SeasonID) || is_null($item->RecordType)) continue;
            
            $rules[$item->SeasonID] = $item->RecordType;
            $seasonIDs[] = $item->SeasonID;
        }

         // 檢查還沒派過獎的賽季
        $QualifyingSeason = AccessorFactory::Main()
                                            ->FromTable('QualifyingSeasonData')
                                            ->WhereIn('SeasonID', $seasonIDs)
                                            ->WhereEqual('Status', 0)
                                            ->WhereEqual('Assign', 0)
                                            ->FetchAll();

        if (empty($QualifyingSeason))
        {
            return false;
        }

        // 取出可以派獎的賽季資料
        $seasons = [];
        foreach($QualifyingSeason as $data)
        {
            $season = new stdClass();
            $season->id = $data->SeasonID;
            $season->lobby = $data->Lobby;
            $season->recodeType = $rules[$data->SeasonID];
            $season->result = "skipped";

            $seasons[] = $season;
        }
        
        return $seasons;
    }

    // 取得名次獎勵
    private function GetReward(int $seasonID) : array|false {
        

        // 取得賽季表
        $rows = AccessorFactory::Static()->FromTable('SeasonRankingRewardNew')
                                        ->WhereEqual('SeasonID', $seasonID)
                                        ->FetchAll();

        if(empty($rows))
        {
            return false;
        }

        $rewardTable = [];

        try
        {
            foreach($rows as $reward)
            {
                $rewardHandler = new RewardHandler($reward->RewarID);
                $rewardTable[$reward->Rank] = array_values($rewardHandler->GetItems());
            }
        }
        catch(Exception $ex)
        {
            echo $ex.PHP_EOL;
            return false;
        }
        

        // 檢查獎勵清單是否有誤(名次獎勵跳號)
        $total = count($rewardTable);

        for($ranking = 1; $ranking <= $total; ++$ranking)
        {
            if(array_key_exists($ranking, $rewardTable) == false)
            {
                return false;
            }          
        }

        return $rewardTable;
    }

    // 取得名次
    private function GetRanking(int $seasonID, int $lobby, int $recordType, int $count) : array|false {

        if (array_key_exists($recordType, self::LeadRateFunc) == false)
        {
            return false;
        }

        $func = self::LeadRateFunc[$recordType];
        return $func($seasonID, $lobby, $count);
    }

    private function GetSeasonRankingRewardMailID(int $lobby) : int {

        $config = ConfigGenerator::Instance();
        $mailIDs = json_decode($config->SeasonRankingRewardMailID);
        if (isset($mailIDs->{$lobby}) == false) return 0;
        return $mailIDs->{$lobby};
    }

    // 派獎
    private function Award(int $seasonID, int $lobby, int $recordType) : string {
        
        $rewards = $this->GetReward($seasonID);
        if($rewards == false)
        {
            $this->UpdateStatus($seasonID, SeasonRewardValue::RewardDataFailure);
            return 'failure, Reward Data Error';
        }

        $total = count($rewards);

        $rankings = $this->GetRanking($seasonID, $lobby, $recordType, $total);
        if(empty($rankings))
        {
            $this->UpdateStatus($seasonID, SeasonRewardValue::NoRankingData);
            return 'failure, No Ranking Data';
        }

        $rewardMailID = $this->GetSeasonRankingRewardMailID($lobby);
        if($rewardMailID == 0)
        {
            $this->UpdateStatus($seasonID, SeasonRewardValue::MailIDFailure);
            return 'failure, Mail ID Is Not Exist';
        }

        $config = ConfigGenerator::Instance();
        $mailsHandler = new MailsHandler();

        $logAccessor = AccessorFactory::Log();
        $logAccessor->FromTable('SeasonRankingReward');

        foreach($rankings as $ranking)
        {
            if(array_key_exists($ranking->rank, $rewards) == false) continue;

            $items = $rewards[$ranking->rank];

            $userMailID = $mailsHandler->AddMail($ranking->userId, $rewardMailID, $config->SeasonRankingRewardMailDay);
            $mailsHandler->AddMailItems($userMailID, $items);

            $content = array_map(function($val){
                return [
                    'ItemID' => $val->ItemID,
                    'Amount' => $val->Amount
                ];
            }, $items);
                
            $logContent = [
                'SeasonID' => $seasonID,
                'Lobby' => $lobby,
                'Ranking' => $ranking->rank,
                'UserID' => $ranking->userId,
                'PlayerID' => $ranking->petaId,
                'Content' => json_encode($content),
                'LogTime' => $GLOBALS[Globals::TIME_BEGIN],
            ];

            $logAccessor->Add($logContent);

        }

        

        $this->UpdateStatus($seasonID, SeasonRewardValue::Success);

        return 'success, award '.count($rankings).' times for this season';
        
    }

    private function UpdateStatus(int $seasonID, int $code)
    {
        AccessorFactory::Main()->FromTable('QualifyingSeasonData')
                                ->WhereEqual('SeasonID', $seasonID)
                                ->Modify(['Assign' => $code,]);
    }
}
