<?php

namespace Games\Users;

use stdClass;
use Games\Pools\RewardPool;
use Games\Exceptions\ItemException;
use Games\Consts\RewardValue;

class RewardHandler
{

    private RewardPool $pool;
    private stdClass|false $info;

    private array |null $items;

    public function __construct(int $rewardID)
    {
        $this->pool = RewardPool::Instance();
        $this->info = $this->pool->{ $rewardID};
        $this->items = null;
        $this->GetItems();
        if ($this->info == false) {
            throw new ItemException(ItemException::UseRewardIDError, ['[rewardID]' => $rewardID]);
        }
    }
    
    public function GetInfo() : stdClass|false{
        return $this->info;
    }

    private function AddTempItems(stdClass $content, array $tempItems): array
    {
        if (isset($tempItems[$content->ItemID])) {
            $tempItems[$content->ItemID]->Amount += $content->Amount;
        }
        else {
            $tempItems[$content->ItemID] = clone $content;
        }
        return $tempItems;
    }

    public function ReSetItems()
    {
        $this->items = null;
    }

    public function GetItems(): array
    {
        if ($this->items == null) {
            $tempItems = [];
            switch ($this->info->Modes) {
                case RewardValue::ModeAll: //1：發放所有獎勵(物品ID累加)
                    foreach ($this->info->Contents as $content) {
                        $content->Amount = $content->Amount * $this->info->Times;
                        $tempItems = $this->AddTempItems($content, $tempItems);
                    }
                    break;
                case RewardValue::ModeSelfSelect: //2：玩家自選獎勵(物品ID可重複)
                    foreach ($this->info->Contents as $content) {
                        $content->Amount = $content->Amount * $this->info->Times;
                        $tempItems[] = $content;
                    }
                    break;
                case RewardValue::ModeRandWeight: //3：依權重隨機挑選獎勵(物品ID累加)
                    for ($i = 0; $i < $this->info->Times; $i++) {
                        $rnd = rand(1, $this->info->TotalProportion);
                        foreach ($this->info->Contents as $content) {
                            $rnd = $rnd - $content->Proportion;
                            if ($rnd <= 0) {
                                $tempItems = $this->AddTempItems($content, $tempItems);
                                break;
                            }
                        }
                    }
                    break;
                case 4://企劃表定功能後移除
                    break;
                case RewardValue::ModeRandProb: //5：依機率隨機挑選獎勵(物品ID累加)
                    for ($i = 0; $i < $this->info->Times; $i++) {
                        foreach ($this->info->Contents as $content) {
                            $rnd = rand(1, 1000);
                            if ($rnd <= $content->Proportion) {
                                $tempItems = $this->AddTempItems($content, $tempItems);
                            }
                        }
                    }
                    break;
            }
            $this->items = $tempItems;
        }
        return $this->items;
    }


    public function GetSelectReward(int $selectIndex): stdClass|bool
    {
        if (isset($this->items[$selectIndex]) == false) {
            return false;
        }
        return $this->items[$selectIndex];
    }
}