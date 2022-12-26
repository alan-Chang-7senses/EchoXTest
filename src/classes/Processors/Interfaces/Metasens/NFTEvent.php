<?php

namespace Processors\Interfaces\Metasens;

use Consts\ErrorCode;
use Consts\Globals;
use Consts\Sessions;
use Exception;
use Games\NFTs\NFTFactory;
use Games\NFTs\NFTUtility;
use Games\NFTs\NFTCertificate;
use Games\NFTs\NFTItem;
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
        
        if(method_exists($this, $payload->type)) {
            $this->{$payload->type}($payload);
        }
        else {
            throw new Exception ("Unknonw type {$payload->type}", ErrorCode::ParamError);
        }
        
        $result = new ResultData(ErrorCode::Success);
//        $result->payload = $payload;
        return $result;
    }
    
    private function nftContractsUpdated(stdClass $payload) : void{
        
    }
    
    private function nftCreated(stdClass $payload) : void{
        /*
        $userID = $_SESSION[Sessions::UserID];
        $nftFactory = new NFTFactory($_SESSION[Sessions::UserID]);
        $currentTime = $GLOBALS[Globals::TIME_BEGIN];
        */
    }
    
    private function nftMetadataChanged(stdClass $payload) : void{
        
    }
    
    private function nftOwnerChanged(stdClass $payload) : void{
        
    }
    
    private function emailBound(stdClass $payload) : void{
        
    }

    private function itemRedeemed(stdClass $payload) : void{
        NFTItem::CheckPayload($payload);

        // 檢查是否已經處理
        $serial = NFTItem::IsPayloadHandle($payload);
        if ($serial !== NFTItem::LogNotExist) 
        {
            //echo "this payload is handle, serial = {$serial}".PHP_EOL;
            return;
        }

        // 在一次檢查玩家在遊戲中是否已經有帳號
        $user = NFTCertificate::UserExist(($payload->data->email));
        if ($user == false)
        {
            // 沒有帳號，可能為先購買 NFT 再創角
            NFTItem::AddLog($payload, 0, "user : {$payload->data->email}, is not exist.");
            throw new Exception ("Unknonw email {$payload->data->email}", ErrorCode::ParamError);
            return;
        }

        // 道具發佈
        NFTItem::DeployItems($user->UserID, $payload);        
    }

    
}
