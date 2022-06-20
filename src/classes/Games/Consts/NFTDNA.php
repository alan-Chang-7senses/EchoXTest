<?php

namespace Games\Consts;
/**
 * Description of NFTDNA
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class NFTDNA {
    
    /** NFT DNA 外觀部位起始碼位置 */
    const PartStart = 0;
    /** NFT DNA 外觀部位碼長度 */
    const PartLength = 6;
    
    /** NFT DNA 主要分段切割長度（幾碼一段） */
    const MainSplitLength = 8;
    /** NFT DNA 主要分段 顯性起始碼偏移位置 */
    const DominantOffset = 0;
    /** NFT DNA 主要分段 第一隱性起始碼偏移位置 */
    const RecessiveOneOffset = 8;
    /** NFT DNA 主要分段 第隱性起始碼偏移位置 */
    const RecessiveTwoOffset = 16;
    
    /** 屬性適性偏移位置 */
    const AttrAdaptOffset = 2;
    /** 屬性適性長度 */
    const AttrAdaptLength = 2;
    
    /** 物種適性偏移位置 */
    const SpeciesAdaptOffset = 0;
    /** 物種適性長度 */
    const SpeciesAdaptLength = 1;
    
    /** 來源標記第一碼 - 推廣用 */
    const SourcePromote = 0;
    /** 來源標記第一碼 - 正常付費購買產出 */
    const SourceNormal = 1;
    /** 來源標記第一碼 - 繁殖產出 */
    const SourceBreed = 2;
    
    /** 來源標記第二碼 - 強度級別 */
    const StrengthS = 0;
    const StrengthPlusA = 1;
    const StrengthNormalA = 2;
    const StrengthMinusA = 3;
    const StrengthPlusB = 4;
    const StrengthNormalB = 5;
    const StrengthMinusB = 6;
    const StrengthPlusC = 7;
    const StrengthNormalC = 8;
    const StrengthMinusC = 9;
}
