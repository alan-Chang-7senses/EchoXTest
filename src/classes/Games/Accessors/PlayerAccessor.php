<?php

namespace Games\Accessors;

/**
 * Description of PlayerAccessor
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class PlayerAccessor extends BaseAccessor{
    
    public function countHolderByUserID(int $id) : int{
        $row = $this->MainAccessor()->SelectExpr('COUNT(*) AS cnt')->FromTable('PlayerHolder')->WhereEqual('UserID', $id)->Fetch();
        if(empty($row)) return 0;
        return $row->cnt;
    }

    public function rowPlayerJoinHolderByUserID(int $id) : mixed{
        return $this->MainAccessor()->FromTableJoinUsing('PlayerNFT', 'PlayerHolder', 'INNER', 'PlayerID')
                ->WhereEqual('UserID', $id)->Fetch();
    }

    public function rowPlayerJoinHolderByUserAndPlayerID(int $userID, int $playerID) : mixed{
        return $this->MainAccessor()->FromTableJoinUsing('PlayerNFT', 'PlayerHolder', 'INNER', 'PlayerID')
                ->WhereEqual('PlayerID', $playerID)->WhereEqual('UserID', $userID)
                ->Fetch();
    }
    
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
    
    /**
     * 透過角色ID和技能ID取得多筆角色技能等級與裝備資料
     * @param int $playerID
     * @param array $skillIDs
     * @return array
     */
    public function rowsSkillByPlayerIDAndSkillIDs(int $playerID, array $skillIDs) : array{
        return $this->MainAccessor()->FromTable('PlayerSkill')
                ->WhereEqual('PlayerID', $playerID)
                ->WhereIn('SkillID', $skillIDs)->FetchAll();
    }
    
    public function rowsPlayerJoinHolderByUserIDLimit(int $userID, int $offset = 0, int $count = 1) : array{
        return $this->MainAccessor()->FromTableJoinUsing('PlayerNFT', 'PlayerHolder', 'INNER', 'PlayerID')
                ->WhereEqual('UserID', $userID)->OrderBy('PlayerID')->Limit($count, $offset)->FetchAll();
    }
}
