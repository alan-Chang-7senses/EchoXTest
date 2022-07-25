<?php

namespace Processors\User\FreePeta;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\Sessions;
use Consts\SetUserNicknameValue;
use Exceptions\NormalException;
use Games\Exceptions\UserException;
use Holders\ResultData;
use Processors\BaseProcessor;
use Processors\User\FreePeta\Const\FreePetaValue;
use Processors\User\SetUserNickname;

class Get3FreePeta extends BaseProcessor
{
    public function Process(): ResultData
    {
        $pdo = new PDOAccessor(EnvVar::DBMain);
        $row = $pdo->FromTable("FreePetaProcess")
            ->WhereEqual("UserID", $_SESSION[Sessions::UserID])
            ->Fetch();

        if ($row == false)
            throw new UserException(UserException::UserNameNotSetYet, ["user" => $_SESSION[Sessions::UserID]]);

        $pdo = new PDOAccessor(EnvVar::DBStatic);
        $table = $pdo->FromTable("FreePetaInfo")->FetchAll();
        $freePetas = array();
        foreach ($table as $row) 
        {
            $freePetas[$row->Type][] = $row;
        }
        $free3Peta = [];
        for($i = 0; $i < FreePetaValue::FreePetaTypeCount; ++$i)
        {
            $count = count($freePetas[$i]);
            $r = rand(0,$count - 1);            
            $free3Peta[] = $freePetas[$i][$r];
        }
        //TODO：把row的資料轉換成前端需要的。        
        $results = new ResultData(ErrorCode::Success);
        return $results;
    }

}
