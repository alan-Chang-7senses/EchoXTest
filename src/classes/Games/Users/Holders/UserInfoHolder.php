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
    public int $vitality;
    public int $money;
    public int $player;
    public int $scene;
    public int $race;
    public array $players;
}
