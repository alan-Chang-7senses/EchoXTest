<?php

namespace Games\NFTs\Holders;

/**
 * Description of PartSkillHolder
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class PartSkillHolder {
    
    public array $aliasCodes = [];
    public array $totalSkillffixID = [];
    
    public function SortFirstAffixID() : int{
        arsort($this->totalSkillffixID);
        reset($this->totalSkillffixID);
        return key($this->totalSkillffixID);
    }
    
    public function NextAffixID() : int{
        next($this->totalSkillffixID);
        return key($this->totalSkillffixID);
    }
}
