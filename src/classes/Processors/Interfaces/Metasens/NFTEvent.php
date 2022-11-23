<?php

namespace Processors\Interfaces\Metasens;

use Consts\ErrorCode;
use Consts\Globals;
use Consts\Sessions;
use Exception;
use Games\NFTs\NFTFactory;
use Games\NFTs\NFTUtility;
use Holders\ResultData;
use Processors\BaseProcessor;
use stdClass;
/**
 * Description of NFTEvent
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class NFTEvent extends BaseProcessor{
    
    protected bool $mustSigned = false;
    
    public function Process(): ResultData {
        
        $headers = getallheaders();
        
        $nftAuthorization = NFTUtility::Authorization();
        if(($headers['Authorization'] ?? $headers['authorization']) != $nftAuthorization)
            throw new Exception ('Authorzation error', ErrorCode::VerifyError);

        $payload = NFTUtility::EventPayload();
        
        if(method_exists($this, $payload->type))
            $this->{$payload->type}($payload);
        
        $result = new ResultData(ErrorCode::Success);
//        $result->payload = $payload;
        return $result;
    }
    
    private function nftContractsUpdated(stdClass $payload) : void{
        
    }
    
    private function nftCreated(stdClass $payload) : void{
        $userID = $_SESSION[Sessions::UserID];
        $nftFactory = new NFTFactory($_SESSION[Sessions::UserID]);
        $currentTime = $GLOBALS[Globals::TIME_BEGIN];

    }
    
    private function nftMetadataChanged(stdClass $payload) : void{
        
    }
    
    private function nftOwnerChanged(stdClass $payload) : void{
        
    }
    
    private function emailBound(stdClass $payload) : void{
        
    }
}
