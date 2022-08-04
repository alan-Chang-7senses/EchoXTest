<?php
namespace Games\PVP\Holders;

class TicketInfoHolder {
    /** @var int 大廳種類 */   
    public int $lobby;

    /** @var int 入場卷物品編號 */   
    public int $ticketID;
    /** @var int 入場卷物品數量 */
    public int $amount;
    /** @var int 入場卷領取上限 */
    public int $maxReceive;
    /** @var int 入場卷剩餘可領時間(秒) */
    public int $receiveRemainTime;
}