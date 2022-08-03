<?php

namespace Games\Accessors;

use Consts\Globals;
use stdClass;

class QualifyingSeasonAccessor extends BaseAccessor
{
    public function GetNowSeason(): mixed
    {
        return $this->MainAccessor()->FromTable('QualifyingSeason')->OrderBy('QualifyingSeasonID', 'DESC')->Fetch();
    }

    public function AddNewSeason(int $id, int $startTime, int $endTime): bool
    {
        $value = $this->GetArean($id);

        return $this->MainAccessor()->FromTable('QualifyingSeason')->Add([
            'ArenaID' => $id,
            'PTScene' => $value->PTScene,
            'CoinScene' => $value->CoinScene,
            'StartTime' => $startTime,
            'EndTime' => $endTime,
            'CreateTime' => $GLOBALS[Globals::TIME_BEGIN]
        ]);

    }

    public function GetArean(int $id): stdClass
    {
        $ptScenes = $this->StaticAccessor()->FromTable('QualifyingArena')->selectExpr('`QualifyingArenaID`,`PTScene` as SceneID')
            ->WhereCondition("PTScene", "!=", "0")->FetchAll();
        $coinScenes = $this->StaticAccessor()->FromTable('QualifyingArena')->selectExpr('`QualifyingArenaID`,`CoinScene` as SceneID')
            ->WhereCondition("CoinScene", "!=", "0")->FetchAll();

        //id start from 1
        $ptSceneIdx = --$id % count($ptScenes);
        $coinSceneIdx = $id % count($coinScenes);

        $result = new stdClass();
        $result->PTScene = $ptScenes[$ptSceneIdx]->SceneID;
        $result->CoinScene = $coinScenes[$coinSceneIdx]->SceneID;

        return $result;
    }

    public function FindItemAmount($bind): int
    {
        $item = $this->MainAccessor()->executeBindFetch('SELECT * from UserItems WHERE UserID = :UserID AND ItemID = :ItemID', $bind);
        return $item == null ? 0 : $item->Amount;
    }


    public function GetUserTicketInfo(int $userId): mixed
    {
         $result = $this->MainAccessor()->FromTable('UserRewardTimes')->WhereEqual('UserID', $userId)->Fetch();
         if ($result == false)
         {
            $this->MainAccessor()->FromTable('UserRewardTimes')->Add([
                'UserID' => $userId,
                'CoinTime' => 0,
                'PTTime' => 0,
                'CreateTime' => $GLOBALS[Globals::TIME_BEGIN],
                'UpdateTime'  => 0
            ]);
         }
        return $result;
    }

    public function UpdateTicketInfo(int $userId, array $bind): bool
    {
        $bind['UpdateTime'] = $GLOBALS[Globals::TIME_BEGIN];
        return $this->MainAccessor()->FromTable('UserRewardTimes')->WhereEqual('UserID', $userId)->Modify($bind);
    }

    public function GetRange(string $kind)
    {
        $statement = str_replace("[Kind]", $kind,
        'SELECT UNIX_TIMESTAMP( STR_TO_DATE(CONCAT ("1970-1-1 ", [Kind]),  "%Y-%m-%d %H:%i:%s")) AS Ranges
        From FreeTicket Where [Kind] is not null AND [Kind] != ""
        ORDER by Ranges');

        $items = $this->StaticAccessor()->executeBindFetchAll($statement, []);
        if ($items == null)
        {
            return false;
        }else {
            $result = [];
            foreach($items as $item)
            {
                $result[] = $item->Ranges;
            };

            return $result;
        }
    }


}