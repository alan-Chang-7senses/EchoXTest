<?php

namespace Games\CommandWorkers;

use Games\Accessors\CLIWorkerAccessor;
use Games\Consts\PlayerValue;
use Games\Players\PlayerUtility;
/**
 * Description of CreatePlayerSkill
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class CreatePlayerSkill extends BaseWorker{
    
    public function Process(): array {
        
        $accessor = new CLIWorkerAccessor();
        
        $rows = $accessor->rowsSkillInfoAccoc();
        $aliasCodeSkillIDs = array_combine(array_column($rows, 'AliasCode'), array_column($rows, 'SkillID'));
        
        $rows = $accessor->rowsSkillPart();
        $skillParts = [];
        foreach($rows as $row) $skillParts[$row->PartCode][$row->PartType] = (object)['aliasCodes' => [$row->AliasCode1, $row->AliasCode2, $row->AliasCode3], 'SkillAffixID' => $row->SkillAffixID];
        
        $rows = $accessor->rowsSkillAffix();
        $skillAffix = [];
        foreach($rows as $row) $skillAffix[$row->SkillAffixID] = $row;
        
        $rowsOld = $accessor->rowsPlayerSkill();
        $rowsNFT = $accessor->rowsPlayerNFT();
        
        $insertRows = [];
        foreach($rowsNFT as $row){
            
            $aliasCodes = [];
            $countAffixID = [];
            
            $this->ProcessSkillPart($skillParts, PlayerUtility::PartCodeByDNA($row->HeadDNA), PlayerValue::Head, $aliasCodes, $countAffixID);
            $this->ProcessSkillPart($skillParts, PlayerUtility::PartCodeByDNA($row->BodyDNA), PlayerValue::Body, $aliasCodes, $countAffixID);
            $this->ProcessSkillPart($skillParts, PlayerUtility::PartCodeByDNA($row->HandDNA), PlayerValue::Hand, $aliasCodes, $countAffixID);
            $this->ProcessSkillPart($skillParts, PlayerUtility::PartCodeByDNA($row->LegDNA), PlayerValue::Leg, $aliasCodes, $countAffixID);
            $this->ProcessSkillPart($skillParts, PlayerUtility::PartCodeByDNA($row->BackDNA), PlayerValue::Back, $aliasCodes, $countAffixID);
            $this->ProcessSkillPart($skillParts, PlayerUtility::PartCodeByDNA($row->HatDNA), PlayerValue::Hat, $aliasCodes, $countAffixID);
            
            arsort($countAffixID);
            reset($countAffixID);
            $affixID = key($countAffixID);
            $aliasCodes[] = $skillAffix[$affixID]->{'Level'.$countAffixID[$affixID]};
            
            if($countAffixID[$affixID] == 3){
                
                next($countAffixID);
                $affixID = key($countAffixID);
                if($countAffixID[$affixID] == 3) $aliasCodes[] = $skillAffix[$affixID]->{'Level'.$countAffixID[$affixID]};
            }
            
            $aliasCodes = array_values(array_filter(array_unique($aliasCodes)));
            
            foreach($aliasCodes as $aliasCode) $insertRows[] = ['PlayerID' => $row->PlayerID, 'SkillID' => $aliasCodeSkillIDs[$aliasCode]];
        }
        
        $res['clear'] = $accessor->ClearPlayerSkill();
        $res['insert'] = $accessor->InsertPlayerSkillsWithIgnore($insertRows);
        foreach($rowsOld as $row) $res['update'][$row->PlayerID][$row->SkillID] = $accessor->UpdatePlayerSkillByPlayerSkillID($row->PlayerID, $row->SkillID, ['Level' => $row->Level, 'Slot' => $row->Slot]);
        
        return $res;
    }
    
    private function ProcessSkillPart(array $skillParts, string $partCode, int $partType, array &$aliasCodes, array &$count) : void{
        
        if(!isset($skillParts[$partCode][$partType])) return;
        
        $skillPart = $skillParts[$partCode][$partType];
        $aliasCodes = array_merge($aliasCodes, $skillPart->aliasCodes);
        if(!isset($count[$skillPart->SkillAffixID])) $count[$skillPart->SkillAffixID] = 0;
        ++$count[$skillPart->SkillAffixID];
    }
}
