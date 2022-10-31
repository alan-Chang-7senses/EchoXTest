<?php

namespace Games\Consts;

/**
 * Description of NFTQueryValue
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class NFTQueryValue {
    
    const ContractSymbols = [
        'PETA'
    ];
    
    const StatusSuccess = 0;
    const StatusFailure = 1;
    const StatusUnknow = 2;
    const StatusMaintenance = 3;
    const StatusInvalidCredentials = 4;
    /** 無此信箱或信箱未綁定錢包 - 將查無 NFTs */
    const StatusEmailNotFound = 5;
    
    const StageEgg = 'EGG';
    const StageAdult = 'ADULT';
    
    const AttributesClass = [
        'FIRE' => PlayerAttr::Fire,
        'WATER' => PlayerAttr::Water,
        'WOOD' => PlayerAttr::Wood,
    ];
    
    const DNAPartHeadOffset = 0;
    const DNAPartHeadLength = 10;
    const DNAPartBodyOffset = 10;
    const DNAPartBodyLength = 10;
    const DNAPartHandOffset = 20;
    const DNAPartHandLength = 10;
    const DNAPartLegOffset = 30;
    const DNAPartLegLength = 10;
    const DNAPartBackOffset = 40;
    const DNAPartBackLength = 10;
    const DNAPartHatOffset = 50;
    const DNAPartHatLength = 10;
    
    const NativeValue = 'YES';
}
