<?php

namespace Processors\Interfaces\Metasens;

use Consts\ErrorCode;
use Exception;
use Games\Accessors\AccessorFactory;
use Games\Exceptions\UserException;
use Games\NFTs\NFTUtility;
use Generators\DataGenerator;
use Holders\ResultData;
use Processors\BaseProcessor;
/**
 * Description of UserExist
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class UserExist extends BaseProcessor{
    
    protected bool $mustSigned = false;
    
    public function Process(): ResultData {
        
        $headers = getallheaders();
        
        $nftAuthorization = NFTUtility::Authorization();
        if(($headers['Authorization'] ?? $headers['authorization']) != $nftAuthorization)
            throw new Exception('Authorzation error', ErrorCode::VerifyError);
        
        $payload = NFTUtility::EventPayload();
        DataGenerator::ExistProperty($payload, 'email');
        
        $accessor = AccessorFactory::Main();
        $row = $accessor->FromTable('Users')->WhereEqual('Email', $payload->email)->Fetch();
        if($row === false) 
            throw new UserException (UserException::UserNotExist, ['[user]' => $payload->email]);
        
        $result = new ResultData(ErrorCode::Success);
        $result->data = [
            'userID' => $row->UserID,
            'ssoID' => $row->Username,
            'nickname' => $row->Nickname,
        ];
        
        return $result;
    }
}
