<?php

namespace Games\FreePlayer;

use Games\Players\Holders\PlayerDnaHolder;
use stdClass;

class FreePlayerInfo extends stdClass
{
    /**多選一時的編號 */
    public ?int $number;
    /**種類 */
    public ?int $type;
    /**屬性 */
    public int $ele;
    /**靈巧 */
    public float $velocity;
    /**體力 */
    public float $stamina;
    /**爆發 */
    public float $breakOut;
    /**鬥志 */
    public float $will;
    /**聰慧 */
    public float $intelligent;
    /**六部位DNA */
    public PlayerDnaHolder|stdClass $dna;
    /**技能詳細資訊集 */
    public array $skills;
    /**基礎資訊(用於存入DB) */
    public stdClass $baseInfo;
}