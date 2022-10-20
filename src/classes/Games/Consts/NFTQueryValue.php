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
    /** 無此信箱或信箱未綁定錢包 - 將查無 NFTs */
    const StatusEmailNotFound = 4;
}
