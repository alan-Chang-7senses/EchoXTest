<?php
namespace Games\PVE\Holders;

use stdClass;

class UserPVEInfoHolder extends stdClass
{
    public int $userID;

    /**已通關關卡資訊。
     * 結構：chapterID =>
     *       [
     *          levelID => medalAmount
     *       ]
     */
    public array $clearLevelInfo;
}