<?php

namespace Games\PVE;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\Sessions;
use Games\Accessors\PVEAccessor;
use Games\Consts\PVEValue;
use stdClass;

class UserPVEChapterHandler
{
    private PVEAccessor $accessor;
    private int $userID;

    public function __construct(int $userID)
    {
        $this->userID = $userID;
        $this->accessor = new PVEAccessor();
    }

    /**
     * @return stdClass 格式：
     *                  firstReward: bool
     *                  secondReward: bool
     *                  thirdReward: bool
     */
    public function GetChapterRewardProcess(int $chapterID) : stdClass
    {
        $rt = new stdClass();
        $rt->firstReward = false;
        $rt->secondReward = false;
        $rt->thirdReward = false;
        $rows = $this->accessor->rowsChapterRewardRecordByUserID($this->userID,$chapterID);
        if($rows === false) return $rt;
        foreach($rows as $row)
        {
            match($row->ChapterRewardID)
            {
                PVEValue::ChapterRewardFirst => $rt->firstReward = true,
                PVEValue::ChapterRewardSecond => $rt->secondReward = true,
                PVEValue::ChapterRewardThird => $rt->thirdReward = true,
            };
        }
        return $rt;
    }

    public function IsRewardAccepted(int $chapterID, int $chapterRewardID) : bool
    {
        $accessor = new PDOAccessor(EnvVar::DBMain);
        $rt = $accessor->FromTable('UserPVEChapterReward',$chapterID)
                 ->WhereEqual('UserID',$_SESSION[Sessions::UserID])
                 ->WhereEqual('ChapterID',$chapterID)
                 ->WhereEqual('ChapterRewardID',$chapterRewardID)
                 ->Fetch();
        return $rt !== false;                 
    }
}