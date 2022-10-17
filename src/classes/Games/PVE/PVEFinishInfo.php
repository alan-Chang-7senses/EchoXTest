<?php

namespace Games\PVE;

class PVEFinishInfo
{
    /**獲得獎牌數量 */
    public int $medalAmount;
    /**獲得物品資訊
     * 每個元素包含物品編號、icon、數量。未獲得則為null
     */
    public array|null $items;
    /**是否成功通關 */
    public bool $isLevelClear;
}