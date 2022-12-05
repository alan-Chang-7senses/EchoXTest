<?php

namespace Games\Accessors;

use Consts\Globals;
use Games\Consts\RaceValue;
use stdClass;

class QualifyingSeasonAccessor extends BaseAccessor
{
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

    public function GetUserTicketInfo(int $userId): mixed
    {
        $result = $this->MainAccessor()->FromTable('UserRewardTimes')->WhereEqual('UserID', $userId)->Fetch();
        if ($result == false) {
            $this->MainAccessor()->FromTable('UserRewardTimes')->Add([
                'UserID' => $userId,
                'CoinTime' => 0,
                'PTTime' => 0,
                'CreateTime' => $GLOBALS[Globals::TIME_BEGIN],
                'UpdateTime' => 0
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
        if ($items == null) {
            return false;
        }
        else {
            $result = [];
            foreach ($items as $item) {
                if ($item->Ranges !== null)
                    $result[] = $item->Ranges;
            }
            ;

            return (count($result) != 0) ? $result : false;
        }
    }

    public function GetQualifyingData(): mixed
    {
        $nowtime = (int) $GLOBALS[Globals::TIME_BEGIN];
        $result = $this->StaticAccessor()->FromTable('QualifyingData')
                                         ->WhereCondition('StartTime','<=', $nowtime)
                                         ->WhereGreater('EndTime', $nowtime)
                                         ->OrderBy('Lobby')
                                         ->FetchAll();

        // echo '[Mingo][QualifyingSeasonAccessor][GetQualifyingData] search from QualifyingData ...'.PHP_EOL;
        // foreach ($result as $key => $val) {
        //     echo $key.' => SeasonID('.$val->SeasonID.'), SeasonName('.$val->SeasonName.'), Lobby('.$val->Lobby.'), StartTime('.$val->StartTime.'), EndTime('.$val->EndTime.')'.PHP_EOL;
        // }

        return $result;
    }

    public function GetSeasonRecordType(): mixed
    {
        $rows = $this->StaticAccessor()->SelectExpr('SeasonID, RecordType')
                                         ->FromTable('Leaderboard')
                                         ->FetchAll();

        $result = [];
                                         
        foreach($rows as $row)
        {
            $result[$row->SeasonID] = $row->RecordType;
        }        

        return $result;
    }
    
    public function GetOpenQualifyingDataByLobby(int $lobby): mixed
    {
        $nowtime = (int) $GLOBALS[Globals::TIME_BEGIN];
        $result = $this->StaticAccessor()->FromTable('QualifyingData')
                                         ->WhereEqual('Lobby', $lobby)
                                         ->WhereCondition('StartTime','<=', $nowtime)
                                         ->WhereGreater('EndTime', $nowtime)
                                         ->Fetch();
        return $result;
    }

    public function GetOpenQualifyingSeasonData(int $lobby): mixed
    {
        return $this->MainAccessor()->FromTable('QualifyingSeasonData')
                                    ->WhereEqual('Lobby', $lobby)
                                    ->WhereEqual('Status', RaceValue::QualifyingSeasonOpen)
                                    ->Fetch();
    }

    public function AddQualifyingSeasonData(int $seasonID, int $lobby): bool
    {
        return $this->MainAccessor()->FromTable('QualifyingSeasonData')->Add([
            'SeasonID' => $seasonID,
            'Lobby' => $lobby,
            'Status' => RaceValue::QualifyingSeasonOpen,
            'Assign' => 0,
            'UpdateTime' => $GLOBALS[Globals::TIME_BEGIN]
        ]);
    }

    public function ModifyQualifyingSeasonData(int $id, array $bind): bool
    {
        return $this->MainAccessor()->FromTable('QualifyingSeasonData')->WhereEqual('SeasonID', $id)->Modify($bind);
    }
}