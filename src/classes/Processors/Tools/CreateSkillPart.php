<?php

namespace Processors\Tools;

use Consts\ErrorCode;
use Games\Accessors\ToolAccessor;
use Games\Consts\PlayerValue;
use Games\Players\PlayerUtility;
use Holders\ResultData;
/**
 * Description of CreateSkillPart
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class CreateSkillPart extends BaseTools{
    
    public function Process(): ResultData {
        
        $accessor = new ToolAccessor();
        
        $rowsOld = $accessor->rowsSkillPart();
        $accessor->ClearSkillPart();
        
        $rowsNFT = $accessor->rowsPlayerNFT();
        $rows = [];
        $check = [];
        foreach($rowsNFT as $row){
            
            $this->AddSkillPart(PlayerValue::Head, PlayerUtility::PartCodeByDNA($row->HeadDNA), $rows, $check);
            $this->AddSkillPart(PlayerValue::Body, PlayerUtility::PartCodeByDNA($row->BodyDNA), $rows, $check);
            $this->AddSkillPart(PlayerValue::Hand, PlayerUtility::PartCodeByDNA($row->HandDNA), $rows, $check);
            $this->AddSkillPart(PlayerValue::Leg, PlayerUtility::PartCodeByDNA($row->LegDNA), $rows, $check);
            $this->AddSkillPart(PlayerValue::Back, PlayerUtility::PartCodeByDNA($row->BackDNA), $rows, $check);
            $this->AddSkillPart(PlayerValue::Hat, PlayerUtility::PartCodeByDNA($row->HatDNA), $rows, $check);
        }
        
        $res['add'] = $accessor->AddSkillParts($rows);
        
        foreach($rowsOld as $row){
            
            $res['modify'][] = $accessor->ModifySkillPartByPart($row->PartCode, $row->PartType, [
                'AliasCode1' => $row->AliasCode1,
                'AliasCode2' => $row->AliasCode2,
                'AliasCode3' => $row->AliasCode3,
            ]);
        }
        
        $result = new ResultData(ErrorCode::Success);
        $result->oldData = $rowsOld;
        $result->res = $res;
        
        return $result;
    }
    
    private function AddSkillPart(int $part, string $partCode, array &$rows, array &$check) : void{
        if(!empty($check[$partCode.$part])) return;
        $rows[] = ['PartCode' => $partCode, 'PartType' => $part];
        $check[$partCode.$part] = true;
    }
}
