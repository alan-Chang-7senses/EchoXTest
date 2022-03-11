<?php

namespace Games\Accessors;

use PDO;
/**
 * Description of PlayerAccessor
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class PlayerAccessor extends BaseAccessor{
    
    /**
     * 透過角色ID取得包含持有者與角色等級的單筆資料
     * @param int $id
     * @return mixed
     */
    public function rowPlayerJoinHolderLevelByPlayerID(int $id) : mixed{
        return $this->MainAccessor()->FromTableJoinUsing('PlayerNFT', 'PlayerHolder', 'INNER', 'PlayerID')
                ->FromTableJoinUsingNext('PlayerLevel', 'LEFT', 'PlayerID')
                ->WhereEqual('PlayerID', $id)->Fetch();
    }
    
    public function rowsSkillByPlayerID(int $playerID) : array{
        return $this->MainAccessor()->FromTable('PlayerSkill')->WhereEqual('PlayerID', $playerID)->FetchAll();
    }
    
    public function rowsHolderByUserIDFetchAssoc(int $userID) : array{
        return $this->MainAccessor()->FromTable('PlayerHolder')
                ->WhereEqual('UserID', $userID)->FetchStyle(PDO::FETCH_ASSOC)->FetchAll();
    }
}
