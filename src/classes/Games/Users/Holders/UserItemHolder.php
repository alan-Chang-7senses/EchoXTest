<?php

namespace Games\Users\Holders;

/**
 * Description of UserItemHolder
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class UserItemHolder extends \stdClass{
    public int $id;
    public int $user;
    public int $itemID;
    public int $amount;
    public int $createTime;
    public int $updateTime;
    public string $itemName;
    public string $description;
    public int $itemType;
    public string $icon;
    public int $stackLimit;
    public int $useType;
    public int $effectType;
    public int $effectValue;
    public int $rewardID;
    public array $source;
}
