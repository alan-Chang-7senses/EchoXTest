<?php

namespace Games\Accessors;

/**
 * Description of PlayerAccessor
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class PlayerAccessor extends BaseAccessor{
    
    /**
     * 透過角色ID取得包含持有者的單筆資料
     * @param string $id
     * @return mixed
     */
    public function rowPlayerJoinHolderByCharacterID(string $id) : mixed{
        return $this->MainAccessor()->FromTableJoinUsing('CharacterNFT', 'CharacterHolder', 'INNER', 'CharacterID')
                ->FromTableJoinUsingNext('CharacterLevel', 'LEFT', 'CharacterID')
                ->WhereEqual('CharacterID', $id)->Fetch();
    }
    
    /**
     * 透過角色ID和技能ID取得多筆角色技能等級與裝備資料
     * @param string $characterID
     * @param array $skillIDs
     * @return array
     */
    public function rowsSkillByCharacterIDAndSkillIDs(string $characterID, array $skillIDs) : array{
        return $this->MainAccessor()->FromTable('CharacterSkill')->WhereEqual('CharacterID', $characterID)
                ->WhereIn('SkillID', $skillIDs)->FetchAll();
    }
}
