<?php
namespace Processors\Tools;

use Consts\ErrorCode;
use Games\Accessors\SkillAccessor;
use Games\Accessors\ToolAccessor;
use Games\Consts\PlayerValue;
use Games\Players\PlayerUtility;
use Holders\ResultData;
/**
 * Description of CreatePlayerSkill
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class CreatePlayerSkill extends BaseTools {
    
    public function Process(): ResultData {
        
        $accessor = new ToolAccessor();
        
        $rowsOld = $accessor->rowsPlayerSkill();
        
        $rowsNFT = $accessor->rowsPlayerNFT();
        $accessorSkill = new SkillAccessor();
        $playerSkillAlias = [];
        $allAliasCodes = [];
        foreach($rowsNFT as $row){
            
            $aliasCodes = [];
            $aliasCodes = array_merge($aliasCodes, $accessorSkill->aliasCodesByPart(PlayerUtility::PartCodeByDNA($row->HeadDNA), PlayerValue::Head));
            $aliasCodes = array_merge($aliasCodes, $accessorSkill->aliasCodesByPart(PlayerUtility::PartCodeByDNA($row->BodyDNA), PlayerValue::Body));
            $aliasCodes = array_merge($aliasCodes, $accessorSkill->aliasCodesByPart(PlayerUtility::PartCodeByDNA($row->HandDNA), PlayerValue::Hand));
            $aliasCodes = array_merge($aliasCodes, $accessorSkill->aliasCodesByPart(PlayerUtility::PartCodeByDNA($row->LegDNA), PlayerValue::Leg));
            $aliasCodes = array_merge($aliasCodes, $accessorSkill->aliasCodesByPart(PlayerUtility::PartCodeByDNA($row->BackDNA), PlayerValue::Back));
            $aliasCodes = array_merge($aliasCodes, $accessorSkill->aliasCodesByPart(PlayerUtility::PartCodeByDNA($row->HatDNA), PlayerValue::Hat));
            $aliasCodes = array_filter(array_unique($aliasCodes));
            
            $playerSkillAlias[$row->PlayerID] = $aliasCodes;
            
            $allAliasCodes = array_merge($allAliasCodes, $aliasCodes);
        }
        
        $allAliasCodes = array_values(array_unique($allAliasCodes));
        $rowsSkillInfo = $accessorSkill->rowsInfoByAliasCodes($allAliasCodes, true);
        $skillIDs = array_combine(array_column($rowsSkillInfo, 'AliasCode'), array_column($rowsSkillInfo, 'SkillID'));
        
        $addData = [];
        foreach($playerSkillAlias as $playerID => $aliasCodes){
            foreach($aliasCodes as $aliasCode) $addData[] = ['PlayerID' => $playerID, 'SkillId' => $skillIDs[$aliasCode]];
        }
        
        $res['clear'] = $accessor->ClearPlayerSkill();
        $res['add'] = $accessor->AddPlayerSkills($addData);
        foreach($rowsOld as $row) $res['modify'][$row->PlayerID][$row->SkillID] = $accessor->ModifyPlayerSkillByPlayerAndSkill($row->PlayerID, $row->SkillID, ['Level' => $row->Level, 'Slot' => $row->Slot]);
        
        $result = new ResultData(ErrorCode::Success);
        $result->res = $res;
        
        return $result;
    }
}
