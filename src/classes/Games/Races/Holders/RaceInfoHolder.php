<?php
namespace Games\Races\Holders;

use stdClass;
/**
 * Description of RaceInfoHolder
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class RaceInfoHolder extends stdClass{
    /** @var int 競賽編號 RaceID */
    public int $id;
    /** @var int 狀態 */
    public int $status;
    /** @var int 場景編號 SceneID */
    public int $scene;
    /** @var int 當前天氣 */
    public int $weather;
    /** @var int 當前風向 */
    public int $windDirection;
    /** @var int 競賽角色資料編號 PlayerID->RacePlayerID */
    public stdClass $racePlayers;
    public float $createTime;
    public float $updateTime;
    public float $finishTime;
}
