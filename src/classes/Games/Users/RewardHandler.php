<?php

namespace Games\Users;

use stdClass;
use Games\Pools\RewardPool;

class RewardHandler
{

    private RewardPool $pool;
    public stdClass|false $info;


    public function __construct()
    {
        $this->pool = RewardPool::Instance();
    }

    private function GetInfo(int|string $rewardID)
    {
        $this->info = $this->pool->{ $rewardID};
    }

    public function GetItems(int|string $rewardID): array
    {
        $this->GetInfo($rewardID);

        if ($this->info == false) {
            return [];
        }

        $tempItems = [];
        for ($i = 0; $i < $this->info->Times; $i++) {

            switch ($this->info->Modes) {
                case 1: //1：發放所有獎勵                    
                case 2: //2：玩家自選獎勵                    
                    foreach ($this->info->Contents as $content) {
                        $tempItems[$content->ItemID] = $tempItems[$content->ItemID] + $content->Amount;
                    }
                    break;
                case 3: //3：依權重隨機挑選獎勵
                    $rnd = rand(1, $this->info->TotalProportion);
                    foreach ($this->info->Contents as $content) {
                        $rnd = $rnd - $content->Proportion;
                        if ($rnd <= 0) {
                            $tempItems[$content->ItemID] = $tempItems[$content->ItemID] + $content->Amount;
                            break;
                        }
                    }
                    break;
                case 5: //5：依機率隨機挑選獎勵
                    foreach ($this->info->Contents as $content) {
                        $rnd = rand(1, 1000);
                        if ($rnd <= $content->Proportion) {
                            $tempItems[$content->ItemID] = $tempItems[$content->ItemID] + $content->Amount;
                        }
                    }
                    break;
            }
        }

        $itemsArray = [];
        foreach ($tempItems as $key => $value) {
            $item = new stdClass();
            $item->ItemID = $key;
            $item->Amount = $value;
            $itemsArray[] = $item;
        }
        return $itemsArray;
    }

    public function AddReward(int $userid, int $rewardID)
    {

        $addItems = $this->GetItems($rewardID);
        foreach ($addItems as $addItem) {
            if ($addItem->ItemID > 0) {
                $userBagHandler = new UserBagHandler($userid);
                //todo refactor AddItem
                $userBagHandler->AddItem($userid, $addItem->ItemID, $addItem->Amount);
            }
            else if ($addItem->ItemID < 0) {
                $userHandler = new UserHandler($userid);
                $info = $userHandler->GetInfo();
                switch ($addItem->ItemID) {
                    case -1: //-1 電力
                        $info->power += $addItem->Amount;
                        $userHandler->SaveData(['Power' => $info->power]);
                        break;
                    case -2: //-2 金幣
                        $info->coin += $addItem->Amount;
                        $userHandler->SaveData(['Coin' => $info->power]);
                        break;
                    case -3: //-3 寶石
                        $info->diamond += $addItem->Amount;
                        $userHandler->SaveData(['Diamond' => $info->power]);
                        break;
                    case -4: //-4 PT
                        $info->petaToken += $addItem->Amount;
                        $userHandler->SaveData(['PetaToken' => $info->power]);
                        break;
                }
            }
        }
    }
}