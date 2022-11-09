<?php

namespace Games\NFTs\Holders;

/**
 * Description of MetadataDNAHolder
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class MetadataDNAHolder {
    
    public string $dPart;
    public string $rPart1;
    public string $rPart2;
    
    public function __construct(string $dPart, string $rPart1, string $rPart2) {
        $this->dPart = $dPart;
        $this->rPart1 = $rPart1;
        $this->rPart2 = $rPart2;
    }
}
