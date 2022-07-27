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
            'UpdateTime' => $GLOBALS[Globals::TIME_BEGIN]
        ]);

    }

    public function GetArean(int $id): stdClass
    {
        $ptScenes = $this->StaticAccessor()->FromTable('QualifyingArena')->selectExpr('`QualifyingArenaID`,`PTScene` as SceneID')
            ->WhereCondition("PTScene", "!=", "null")->FetchAll();
        $coinScenes = $this->StaticAccessor()->FromTable('QualifyingArena')->selectExpr('`QualifyingArenaID`,`CoinScene` as SceneID')
            ->WhereCondition("CoinScene", "!=", "null")->FetchAll();

        //id start from 1
        $ptSceneIdx = --$id % count($ptScenes);
        $coinSceneIdx = $id % count($coinScenes);

        $result = new stdClass();
        $result->PTScene = $ptScenes[$ptSceneIdx]->SceneID;
        $result->CoinScene = $coinScenes[$coinSceneIdx]->SceneID;

        return $result;

    }

}