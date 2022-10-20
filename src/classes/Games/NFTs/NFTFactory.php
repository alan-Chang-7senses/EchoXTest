<?php

namespace Games\NFTs;

use Accessors\CurlAccessor;
use Consts\EnvVar;
use Games\Accessors\AccessorFactory;
use Games\Consts\NFTQueryValue;
use Games\Exceptions\NFTException;
use Games\NFTs\Holders\NFTUserHolder;
use Helpers\LogHelper;
use stdClass;
/**
 * Description of NFTFactory
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class NFTFactory {
    
    private NFTUserHolder $userHolder;
    
    public function __construct(int $userID) {
        
        $row = AccessorFactory::Main()->FromTable('Users')->WhereEqual('UserID', $userID);
        $this->userHolder = new NFTUserHolder($row->UserID, $row->Email, $row->Player);
    }
    
    private function QueryNFTs(string $email) : stdClass|false{
        
        $curl = new CurlAccessor(getenv(EnvVar::NFTUri).'/queries/nfts/'.$email.'?'. http_build_query(['nftContractSymbols' => NFTQueryValue::ContractSymbols]));
        $curlReturn = $curl->ExecOptions([
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Authorization: '. self::Authorization()]
        ]);
        
        $nfts = json_decode($curlReturn);
        
        $status = NFTQueryValue::StatusSuccess;
        if($nfts === null) $status = NFTQueryValue::StatusFailure;
        else if(isset($nfts->errors[0]) && $nfts->errors[0]->message == 'IN MAINTENANCE') $status = NFTQueryValue::StatusMaintenance;
        else if(!empty($nfts->error)){
            if($nfts->error == 'emailNotFound') $status = NFTQueryValue::StatusEmailNotFound;
            else $status = NFTQueryValue::StatusUnknow;
        }
        
        LogHelper::Extra('NFTQuery', [
            'status' => $status,
            'nfts' => $nfts
        ]);
        
        if($status != NFTQueryValue::StatusSuccess){
            LogHelper::Save(new NFTException(NFTException::QueryNFTFailure));
            return false;
        }
        
        return $nfts->result;
    }
}
