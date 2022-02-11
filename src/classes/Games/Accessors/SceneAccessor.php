<?php

namespace Games\Accessors;

/**
 * Description of SceneAccessor
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class SceneAccessor extends BaseAccessor{
    
    public function rowInfoByID(int $id) : mixed{
        return $this->StaticAcceessor()->FromTable('SceneInfo')->WhereEqual('SceneID', $id)->Fetch();
    }
    
    public function rowsTrackBySceneID(int $id) : array{
        return $this->StaticAcceessor()->FromTable('SceneTracks')
                ->WhereEqual('SceneID', $id)->OrderBy('SortOrder')->FetchAll();
    }
    
    public function rowsClimateBySceneID(int $id) : array{
        return $this->StaticAcceessor()->FromTable('SceneClimate')
                ->WhereEqual('SceneID', $id)->OrderBy('StartTime', 'DESC')->FetchAll();
    }
}
