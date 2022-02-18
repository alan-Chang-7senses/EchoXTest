<?php
namespace Games\Races\Holders;

use stdClass;
/**
 * Description of RaceInfoHolder
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class RaceInfoHolder {
    /** @var int 競賽編號 RaceID */
    public int $id;
    /** @var int 場景編號 SceneID */
    public int $scene;
    /** @var int 當前風向 */
    public int $windDirection;
    /** @var int 競賽角色資料編號 PlayerID->RacePlayerID */
    public stdClass $racePlayers;
}
