<?php

namespace Games\Users;

use Games\Exceptions\UserException;
use stdClass;
use Games\Pools\RewardPool;
use Google\Service\Script\Content;
use Google\Service\ShoppingContent\Amount;

class RewardHandler
{

    private RewardPool $pool;
    public stdClass|false $info;


    public function __construct(int $rewardID)
    {
        $this->pool = RewardPool::Instance();
        $this->info = $this->pool->{ $rewardID};
        if ($this->info == false) {
            throw new UserException(UserException::UseRewardIDError, ['[rewardID]' => $rewardID]);
        }

    }

    private function AddTempItems(stdClass $content, array $tempItems): array
    {
        if (isset($tempItems[$content->ItemID])) {
            $tempItems[$content->ItemID]->Amount += $content->Amount;
        }
        else {
            $tempItems[$content->ItemID] = $content;
        }
        return $tempItems;
    }

    public function GetItems(): array
    {

        $tempItems = [];
        for ($i = 0; $i < $this->info->Times; $i++) {

            switch ($this->info->Modes) {
                case 1: //1：發放所有獎勵                    
                case 2: //2：玩家自選獎勵                    
                    foreach ($this->info->Contents as $content) {
                        $tempItems = $this->AddTempItems($content, $tempItems);
                    }
                    break;
                case 3: //3：依權重隨機挑選獎勵
                    $rnd = rand(1, $this->info->TotalProportion);
                    foreach ($this->info->Contents as $content) {
                        $rnd = $rnd - $content->Proportion;
                        if ($rnd <= 0) {
                            $tempItems = $this->AddTempItems($content, $tempItems);
                            break;
                        }
                    }
                    break;
                case 5: //5：依機率隨機挑選獎勵
                    foreach ($this->info->Contents as $content) {
                        $rnd = rand(1, 1000);
                        if ($rnd <= $content->Proportion) {
                            $tempItems = $this->AddTempItems($content, $tempItems);
                        }
                    }
                    break;
            }
        }

        return $tempItems;
    }


    public function AddSelectReward(int $userid,int $amount, $selectItemId): stdClass
    {
        if ($this->info->Modes != 2) {
            throw new UserException(UserException::UseRewardIDError, ['[rewardID]' => $this->info->RewardID]);
        }


        if (isset($this->GetItems()[$selectItemId]) == false)
        {
            throw new UserException(UserException::UseRewardIDError, ['[rewardID]' => $this->info->RewardID]);            
        }

        $addItem = $this->GetItems()[$selectItemId];            
        $addItem->Amount = $addItem->Amount *  $amount;

        $this->AddItem($userid, $addItem);
        return $addItem;        
    }

    public function AddReward(int $userid): array
    {
        if ($this->info->Modes == 2) {
            throw new UserException(UserException::UseRewardIDError, ['[rewardID]' => $this->info->RewardID]);
        }

        $addItems = $this->GetItems();        
        foreach ($addItems as $addItem) {
            $this->AddItem($userid, $addItem);
        }
        return $addItems;        
    }

    private function AddItem(int $userid, stdclass $addItem)
    {
        if ($addItem->ItemID > 0) {
            $userBagHandler = new UserBagHandler($userid);        
            $userBagHandler->AddItem($addItem->ItemID, $addItem->Amount);
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