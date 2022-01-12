<?php

namespace Games\Players;

use Games\Consts\NFTDNA;
/**
 * Description of PlayerUtility
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class PlayerUtility {
    
    public static function PartCodeByDNA(string $dna) : string{
        return substr($dna, NFTDNA::PartStart, NFTDNA::PartLength);
    }
}
