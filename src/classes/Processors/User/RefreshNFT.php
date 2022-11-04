<?php
namespace Processors\User;

use Consts\ErrorCode;
use Consts\Sessions;
use Games\NFTs\NFTFactory;
use Holders\ResultData;
use Processors\BaseProcessor;

class RefreshNFT extends BaseProcessor
{
    public function Process(): ResultData
    {
        $result = new ResultData(ErrorCode::Success);
        $userID = $_SESSION[Sessions::UserID];
        $nftFactory = new NFTFactory($userID);
        $nftFactory->RefreshPlayers();
        return $result;
    }
}