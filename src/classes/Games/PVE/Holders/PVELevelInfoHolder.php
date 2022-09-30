<?php
namespace Games\PVE\Holders;

use stdClass;

class PVELevelInfoHolder extends stdClass
{
    /** 關卡ID */
    public int|string $levelID;

    /** 關卡所在章節ID */
    public int|string $chapterID;

    public array|null $preLevels;

    /**推薦角色等級 */
    public int $recommendLevel;

    /** 需求體力 */
    public int $power;

    /** 關卡名稱字碼*/
    public int|string $levelName;

    /** 關卡簡介字碼*/
    public int|string $description;

    /**使用場景ID */
    public int $sceneID;

    /**環境代碼 */
    public int $enviroment;

    /**天氣代碼 */
    public int $weather;

    /**風向代碼 */
    public int $wind;

    /**風速 */
    public int $windSpeed;

    /**日夜代碼 */
    public int $dayNight;

    /**用戶在第幾跑道 */
    public int $userTrackNumber;

    /**初次過關獎勵 */
    public int $firstRewardID;

    /**固定過關獎勵 */
    public int $sustainRewardID;

    /**AI資訊： AIID:賽道編號 */
    public array $aiInfo;
}
