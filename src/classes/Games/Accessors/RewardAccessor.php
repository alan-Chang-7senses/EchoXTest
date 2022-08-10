<?php

namespace Games\Accessors;

class RewardAccessor extends BaseAccessor{   
    public function rowInfoByID(int $id) : mixed{
        return $this->StaticAccessor()->FromTableJoinOn('RewardInfo','RewardContent', 'INNER', 'ContentGroupID', 'ContentGroupID')->
        WhereEqual('RewardID', $id)->OrderBy("ItemID")->FetchAll();      
    }
   
}
