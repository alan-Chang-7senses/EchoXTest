<?php
namespace Games\PVP\Holders;

use stdClass;
/**
 * Description of RaceInfoHolder
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class TicketinfoHolder {
    /** @var int 入場卷物品編號 */
    public int $ticketID;
    /** @var int 入場卷物品數量 */
    public int $amount;
    /** @var int 入場卷領取上限 */
    public int $maxReceive;
    /** @var int 入場卷剩餘可領時間(秒) */
    public int $receiveRemainTime;
}