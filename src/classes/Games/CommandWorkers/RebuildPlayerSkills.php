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
        
        $this->EchoMessage('Connect Static DB');
        $staticAccessor = new PDOAccessor(EnvVar::DBStatic);
        $this->EchoMessage('Connect Static DB', 'Finish');
        
        $rows = $staticAccessor->FromTable('SkillInfo')->FetchStyleAssoc()->FetchAll();
        $aliasCodeSkillIDs = array_combine(array_column($rows, 'AliasCode'), array_column($rows, 'SkillID'));
        $this->EchoMessage('Fetch SkillInfo', [
            'Format' => '[AliasCode => SkillID]',
            'Count' => count($aliasCodeSkillIDs)
        ]);
        
        $rows = $staticAccessor->FromTable('SkillPart')->FetchStyle(PDO::FETCH_OBJ)->FetchAll();
        $skillParts = [];
        foreach($rows as $row) $skillParts[$row->PartCode][$row->PartType] = (object)['aliasCodes' => [$row->AliasCode1, $row->AliasCode2, $row->AliasCode3], 'SkillAffixID' => $row->SkillAffixID];
        $this->EchoMessage('Fetch SkillPart', [
            'Format' => '[PartCode][PartType] => [AliasCodes, SkillAffixID]',
            'Count' => count($skillParts),
        ]);
        
        $rows = $staticAccessor->FromTable('SkillAffixAlias')->FetchAll();
        $skillAffix = [];
        foreach ($rows as $row) $skillAffix[$row->SkillAffixID] = $row;
        $this->EchoMessage('Fetch SkillAffixAlias', [
            'Format' => '[SkillAffixID => row]',
            'Count' => count($skillAffix),
        ]);
        
        $rows = $staticAccessor->FromTable('SkillNative')->FetchAll();
        $skillNative = [];
        foreach($rows as $row) $skillNative[$row->NativeCode] = $row->AliasCode;
        $this->EchoMessage('Fetch SkillNative', [
            'Format' => '[NativeCode => AliasCode',
            'Count' => count($skillNative),
        ]);
        
        $rows = $staticAccessor->FromTable('SkillPurebred')->FetchAll();
        $skillPurebred = [];
        foreach ($rows as $row) $skillPurebred[$row->SpeciesCode] = $row->AliasCode;
        $this->EchoMessage('Fetch SkillPurebred', [
            'Format' => '[SpeciesCode => AliasCode',
            'Count' => count($skillPurebred),
        ]);
        
        $this->EchoMessage('Connect Main DB');
        $mainAccessor = new PDOAccessor(EnvVar::DBMain);
        $this->EchoMessage('Connect Main DB', 'Finish');
        
        $originalPlayerSkills = $mainAccessor->FromTable('PlayerSkill')->FetchAll();
        $nftPlayers = $mainAccessor->FromTable('PlayerNFT')->FetchAll();
        $this->EchoMessage('Fetch PlayerSkill and PlayerNFT', [
            'Original PlayerSkill' => count($originalPlayerSkills),
            'PlayerNFT' => count($nftPlayers),
        ]);
        
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
        
        $insertRowTotal = count($insertRows);
        $insertLastRowCount = count($insertRows[$insertRowTotal - 1]);
        $this->EchoMessage('Process PlayerSkill insert data', [
            'Insert Row Total' => $insertRowTotal,
            'One Row Count' => self::AddAllCount,
            'Insert Total' => ($insertRowTotal - 1) * self::AddAllCount + $insertLastRowCount,
        ]);
        
        $resClear = $mainAccessor->FromTable('PlayerSkill')->Truncate();
        $resInsertTrue = 0;
        $resInsertFalse = 0;
        foreach($insertRows as $rows){
            $insertRes = $mainAccessor->Ignore(true)->AddAll($rows);
            $insertRes ? ++$resInsertTrue : ++$resInsertFalse;
        }
        
        $this->EchoMessage('Truncate and Insert PlayerSkill', [
            'Truncate' => $resClear,
            'Insert' => [
                'True' => $resInsertTrue,
                'False' => $resInsertFalse,
            ]
        ]);
        
        $resUpdateTrue = 0;
        $resUpdateFalse = 0;
        $mainAccessor->PrepareName('UpdaePlayerSkill');
        foreach($originalPlayerSkills as $row){
            
            $updateRes = $mainAccessor->ClearCondition()
                    ->WhereEqual('PlayerID', $row->PlayerID)->WhereEqual('SkillID', $row->SkillID)
                    ->Modify(['Level' => $row->Level, 'LevelBackup' => $row->LevelBackup, 'Slot' => $row->Slot]);
            
            $updateRes ? ++$resUpdateTrue : ++$resUpdateFalse;
        }
        
        $this->EchoMessage('Update PlayerSkill to Original Level', [
            'True' => $resUpdateTrue,
            'False' => $resUpdateFalse,
        ]);
        
        return [
            'Truncate' => $resClear,
            'Insert' => [
                'True' => $resInsertTrue,
                'False' => $resInsertFalse,
            ],
            'Update' => [
                'True' => $resUpdateTrue,
                'False' => $resUpdateFalse,
            ],
        ];
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
