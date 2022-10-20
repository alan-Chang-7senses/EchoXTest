<?php

namespace Games\NFTs\Holders;
/**
 * Description of NFTUserHolder
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class NFTUserHolder {
    
    public int $userID;
    public string $email;
    public int $currentPlayer;
    
    public function __construct(int $userID, string $email, int $currentPlayer) {
        $this->userID = $userID;
        $this->email = $email;
        $this->currentPlayer = $currentPlayer;
    }
}
