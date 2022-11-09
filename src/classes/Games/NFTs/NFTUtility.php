<?php

namespace Games\NFTs;

use Consts\EnvVar;
use Games\Consts\PlayerValue;
use Games\NFTs\Holders\MetadataDNAHolder;
use Games\Consts\NFTQueryValue;
use Games\NFTs\Holders\PartSkillHolder;
/**
 * Description of NFTUtility
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class NFTUtility {
    
    public static function Authorization() {
        return 'Basic '.base64_encode(getenv(EnvVar::NFTClientID).':'.getenv(EnvVar::NFTAPISecret));
    }
    
    public static function EventPayload(){
        return json_decode(file_get_contents('php://input'));
    }
    
    public static function ToPlayerID(int $nftID) : int{
        return $nftID + PlayerValue::NFTIDAddValue;
    }
    
    public static function MetadataDNAToPartDNA(MetadataDNAHolder $holder, int $offset, int $length) : string{
        return substr($holder->dPart, $offset, $length).substr($holder->rPart1, $offset, $length).substr($holder->rPart2, $offset, $length);
    }
    
    public static function HoldPartSkill(array $aliasCodes, int $skillAffixID, PartSkillHolder $holder) : PartSkillHolder{
        
        $holder->aliasCodes = array_merge($holder->aliasCodes, $aliasCodes);
        if(!isset($holder->totalSkillffixID[$skillAffixID])) $holder->totalSkillffixID[$skillAffixID] = 0;
        ++$holder->totalSkillffixID[$skillAffixID];
        
        return $holder;
    }
}
