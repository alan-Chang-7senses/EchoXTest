<?php

namespace Games\CommandWorkers;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use PDO;
use Games\Consts\PlayerValue;
use Games\Players\PlayerUtility;
use Games\Consts\NFTDNA;
/**
 * Description of RebuildPlayerSkills
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class RebuildPlayerSkills extends BaseWorker{
    
    const AddAllCount = 1000;
    
    public function Process(): array {
        
        $staticAccessor = new PDOAccessor(EnvVar::DBStatic);
        
        $rows = $staticAccessor->FromTable('SkillInfo')->FetchStyleAssoc()->FetchAll();
        $aliasCodeSkillIDs = array_combine(array_column($rows, 'AliasCode'), array_column($rows, 'SkillID'));
        
        $rows = $staticAccessor->FromTable('SkillPart')->FetchStyle(PDO::FETCH_OBJ)->FetchAll();
        $skillParts = [];
        foreach($rows as $row) $skillParts[$row->PartCode][$row->PartType] = (object)['aliasCodes' => [$row->AliasCode1, $row->AliasCode2, $row->AliasCode3], 'SkillAffixID' => $row->SkillAffixID];
        
        $rows = $staticAccessor->FromTable('SkillAffixAlias')->FetchAll();
        $skillAffix = [];
        foreach ($rows as $row) $skillAffix[$row->SkillAffixID] = $row;
        
        $rows = $staticAccessor->FromTable('SkillNative')->FetchAll();
        $skillNative = [];
        foreach($rows as $row) $skillNative[$row->NativeCode] = $row->AliasCode;
        
        $rows = $staticAccessor->FromTable('SkillPurebred')->FetchAll();
        $skillPurebred = [];
        foreach ($rows as $row) $skillPurebred[$row->SpeciesCode] = $row->AliasCode;
        
        $mainAccessor = new PDOAccessor(EnvVar::DBMain);
        $originalPlayerSkills = $mainAccessor->FromTable('PlayerSkill')->FetchAll();
        $nftPlayers = $mainAccessor->FromTable('PlayerNFT')->FetchAll();
        
        $insertRows = [];
        $insertCount = 0;
        $insertRowCount = 0;
        foreach ($nftPlayers as $nft) {
            
            $aliasCodes = [];
            $countAffixID = [];
            
            $this->ProcessSkillPart($skillParts, PlayerUtility::PartCodeByDNA($nft->HeadDNA), PlayerValue::Head, $aliasCodes, $countAffixID);
            $this->ProcessSkillPart($skillParts, PlayerUtility::PartCodeByDNA($nft->BodyDNA), PlayerValue::Body, $aliasCodes, $countAffixID);
            $this->ProcessSkillPart($skillParts, PlayerUtility::PartCodeByDNA($nft->HandDNA), PlayerValue::Hand, $aliasCodes, $countAffixID);
            $this->ProcessSkillPart($skillParts, PlayerUtility::PartCodeByDNA($nft->LegDNA), PlayerValue::Leg, $aliasCodes, $countAffixID);
            $this->ProcessSkillPart($skillParts, PlayerUtility::PartCodeByDNA($nft->BackDNA), PlayerValue::Back, $aliasCodes, $countAffixID);
            $this->ProcessSkillPart($skillParts, PlayerUtility::PartCodeByDNA($nft->HatDNA), PlayerValue::Hat, $aliasCodes, $countAffixID);
            
            arsort($countAffixID);
            reset($countAffixID);
            $affixID = key($countAffixID);
            $aliasCodes[] = $skillAffix[$affixID]->{'Level'.$countAffixID[$affixID]};
            
            if($countAffixID[$affixID] == 3){
                
                next($countAffixID);
                $affixID = key($countAffixID);
                if($countAffixID[$affixID] == 3) $aliasCodes[] = $skillAffix[$affixID]->{'Level3'};
            }
            
            if($nft->Native > NFTDNA::NativeNone && isset($skillNative[$nft->Native])) $aliasCodes[] = $skillNative[$nft->Native];
            
            $species = [];
            $this->ProcessSkillPurered($species, PlayerUtility::SpeciesCodeByDNA($nft->HeadDNA));
            $this->ProcessSkillPurered($species, PlayerUtility::SpeciesCodeByDNA($nft->BodyDNA));
            $this->ProcessSkillPurered($species, PlayerUtility::SpeciesCodeByDNA($nft->HandDNA));
            $this->ProcessSkillPurered($species, PlayerUtility::SpeciesCodeByDNA($nft->LegDNA));
            $this->ProcessSkillPurered($species, PlayerUtility::SpeciesCodeByDNA($nft->BackDNA));
            $this->ProcessSkillPurered($species, PlayerUtility::SpeciesCodeByDNA($nft->HatDNA));
            arsort($species);
            reset($species);
            $speciesCode = key($species);
            if($species[$speciesCode] >= NFTDNA::PureredPartCount && isset($skillPurebred[$speciesCode])) $aliasCodes[] = $skillPurebred[$speciesCode];
            
            $aliasCodes = array_values(array_filter(array_unique($aliasCodes)));
            
            foreach($aliasCodes as $aliasCode){
                $insertRows[$insertCount][] = ['PlayerID' => $nft->PlayerID, 'SkillID' => $aliasCodeSkillIDs[$aliasCode]];
                ++$insertRowCount;
                if($insertRowCount >= self::AddAllCount){
                    $insertRowCount = 0;
                    ++$insertCount;
                }
            }
        }
        
        $res['clear'] = $mainAccessor->FromTable('PlayerSkill')->Truncate();
        foreach($insertRows as $rows){
            $res['insert'][] = $mainAccessor->Ignore(true)->AddAll($rows);
        }
        
        foreach($originalPlayerSkills as $row){
            $res['update'][$row->PlayerID][$row->SkillID] = $mainAccessor->PrepareName('UpdaePlayerSkill')
                    ->WhereEqual('PlayerID', $row->PlayerID)->WhereEqual('SkillID', $row->SkillID)
                    ->Modify(['Level' => $row->Level, 'Slot' => $row->Slot]);
        }
        
        return $res;
    }
    
    private function ProcessSkillPart(array $skillParts, string $partCode, int $partType, array &$aliasCodes, array &$count) : void{
        
        if(!isset($skillParts[$partCode][$partType])) return;
        
        $skillPart = $skillParts[$partCode][$partType];
        $aliasCodes = array_merge($aliasCodes, $skillPart->aliasCodes);
        if(!isset($count[$skillPart->SkillAffixID])) $count[$skillPart->SkillAffixID] = 0;
        ++$count[$skillPart->SkillAffixID];
    }
    
    private function ProcessSkillPurered(array &$species, string $speciesCode){
        if(!isset($species[$speciesCode])) $species[$speciesCode] = 0;
        ++$species[$speciesCode];
    }
}
