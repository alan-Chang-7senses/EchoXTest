<?php

namespace Games\Races\Holders;

/**
 * Description of RacePlayerHolder
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class RacePlayerHolder {
    /** @var int 競賽角色編號 RacePlayerID */
    public int $id;
    /** @var int 應愛編號 RaceID */
    public int $race;
    /** @var int 使用者編號 UserID */
    public int $user;
    /** @var int 角色編號 PlayerID */
    public int $player;
    /** @var int 參與比賽號碼順序 */
    public int $number;
    /** @var int 狀態 */
    public int $status;
    /** @var int 角色方向 */
    public int $direction;
    /** @var int 能量 [紅,黃,藍,綠] */
    public array $energy;
    /** @var int 賽道類別 平地、上坡、下坡 */
    public int $trackType;
    /** @var int 賽道形狀 直到、彎道 */
    public int $trackShape;
    /** @var int 比賽節奏 全力衝刺、平常速度、保留體力 */
    public int $rhythm;
    /** @var int 排名 */
    public int $ranking;
    /** @var int 賽道號碼 */
    public int $trackNumber;
    /** @var int 剩餘體力 */
    public int $hp;
}
