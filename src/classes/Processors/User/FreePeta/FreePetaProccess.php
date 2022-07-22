<?php
namespace Processors\User\FreePeta;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\Sessions;
use Consts\SetUserNicknameValue;
use Exceptions\NormalException;
use Holders\ResultData;
use Processors\BaseProcessor;
use Processors\User\SetUserNickname;

class FreePetaProccess extends BaseProcessor
{
    public function Process(): ResultData
    {
        //回傳0代表從取暱稱開始
        //回傳1代表從開盲盒開始
        //回傳2代表免費Peta獲得流程結束        
        $pdo = new PDOAccessor(EnvVar::DBMain);        
        $userID = $_SESSION[Sessions::UserID];
        $row = $pdo->FromTable("FreePetaProccess")
                    ->WhereEqual("UserID", $userID)
                    ->Fetch();
        $proccess = -1;            
        if($row === false)$proccess = SetUserNicknameValue::FirstSet;
        else $proccess = $row->Proccess;
        
        $results = new ResultData(ErrorCode::Success);
        $results->proccess = $proccess;
        return $results;
    }
}