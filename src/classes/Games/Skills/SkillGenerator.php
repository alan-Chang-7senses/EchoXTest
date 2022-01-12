<?php

namespace Games\Skills;

use Games\Accessors\SkillAccessor;
use Games\Consts\SkillPart;
use Games\Players\PlayerUtility;
use Games\Skills\Formula\FormulaFactory;
use stdClass;
/**
 * Description of SkillGenerator
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class SkillGenerator {
    
    public static function aliasCodesByNFT(stdClass $nft) : array {
        
        if(empty($nft)) return [];
        
        $accessor = new SkillAccessor();
        $aliasCodes = $accessor->aliasCodesByPart(PlayerUtility::PartCodeByDNA($nft->HeadDNA), SkillPart::Head);
        $aliasCodes = array_merge($aliasCodes, $accessor->aliasCodesByPart(PlayerUtility::PartCodeByDNA($nft->BodyDNA), SkillPart::Body));
        $aliasCodes = array_merge($aliasCodes, $accessor->aliasCodesByPart(PlayerUtility::PartCodeByDNA($nft->HandDNA), SkillPart::Hand));
        $aliasCodes = array_merge($aliasCodes, $accessor->aliasCodesByPart(PlayerUtility::PartCodeByDNA($nft->LegDNA), SkillPart::Leg));
        $aliasCodes = array_merge($aliasCodes, $accessor->aliasCodesByPart(PlayerUtility::PartCodeByDNA($nft->BackDNA), SkillPart::Back));
        $aliasCodes = array_merge($aliasCodes, $accessor->aliasCodesByPart(PlayerUtility::PartCodeByDNA($nft->HatDNA), SkillPart::Hat));
        
        return array_unique($aliasCodes);
    }
    
    public static function valueByFormuleAndLevelN(string|null $formule, int $levelN) : float|null{
        
        if($formule === null) return null;
        
        $factory = new FormulaFactory($levelN, $formule);
        return $factory->Process();
    }
}
