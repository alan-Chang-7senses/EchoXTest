<?php
namespace Games\Users\Holders;

use stdClass;
/**
 * Description of UserInfoHolder
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class UserInfoHolder extends stdClass{
    public int $id;
    public string $nickname;
    public int $level;
    public int $exp;
    public int $ucg;
    public int $coin;
    public int $power;
    public int $diamond;
    public int $player;
    public int $scene;
    public int $race;
    public int $lobby;
    public int $room;
    public array $players;
    public array $items;
}
