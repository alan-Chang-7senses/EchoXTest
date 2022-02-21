<?php

namespace Games\Accessors;

/**
 * Description of SceneAccessor
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class SceneAccessor extends BaseAccessor{
    
    public function rowInfoByID(int $id) : mixed{
        return $this->StaticAccessor()->FromTable('SceneInfo')->WhereEqual('SceneID', $id)->Fetch();
    }
    
    public function rowsClimateBySceneID(int $id) : array{
        return $this->StaticAccessor()->FromTable('SceneClimate')
                ->WhereEqual('SceneID', $id)->OrderBy('StartTime', 'DESC')->FetchAll();
    }
}
