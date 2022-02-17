<?php
namespace Games\Users\Holders;
/**
 * Description of UserInfoHolder
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class UserInfoHolder {
    public int $id;
    public string $nickname;
    public int $level;
    public int $exp;
    public int $vitality;
    public int $money;
    public int $scene;
    public int $player;
    public int $race;
    public array $players;
}
