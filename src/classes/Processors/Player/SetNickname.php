<?php

namespace Processors\Player;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Holders\ResultData;
use Consts\ErrorCode;
use Consts\Sessions;
use Exception;
use Processors\BaseProcessor;
use Exceptions\NormalException;
use Games\Accessors\PlayerAccessor;
use Games\Players\PlayerHandler;
use Games\Users\UserHandler;
use Helpers\InputHelper;
use Processors\EliteTest\UserInfo;

class SetNickname extends BaseProcessor{

    public function Process(): ResultData
    {
        // input：角色ID，欲更改之角色暱稱
        // 回傳：更改是否成功、成功之暱稱字串。
        $playerID = InputHelper::post('id');
        $nickName = InputHelper::post('nickname');

        //檢查送來暱稱是否合法
        $bandedWords = ['fuck','shit'];//測試用禁字庫
        $this->ValidateName($bandedWords,$nickName);

        $userHandler = new UserHandler($_SESSION[Sessions::UserID]);
        $userInfo = $userHandler->GetInfo();
        $userID = $userInfo->id;

        // //檢查User是否擁有此角色
        $hasPlayer = false;
        foreach($userInfo->players as $player){
            if($player == $playerID)$hasPlayer = true;
        }
        if($hasPlayer === false)throw new Exception("User does not have this player",ErrorCode::Unknown);
        
        // 改名
        $pdo = new PDOAccessor(EnvVar::DBMain);
        $pdo->FromTable('PlayerHolder')
        ->WhereEqual('UserID',$userID)
        ->WhereEqual('PlayerID',$playerID)
        ->Modify(['Nickname' => $nickName]);
        $pdo->ClearCondition();

        
        $results = new ResultData(ErrorCode::Success);
        $nickName = $pdo->FromTable('PlayerHolder')
        ->WhereEqual('PlayerID',$playerID)
        ->Fetch();
        $playerHandler = new PlayerHandler($playerID);
        //更新快取中的暱稱資料
        $playerHandler->GetInfo()->name = $nickName;
        $results->newNickName = ['newNickName' => $nickName->Nickname];
        return $results;
    }

    function ValidateName(array $bandedWord, $nickName){
        $nickNameLength = strlen($nickName);
        if($nickNameLength < 1 || $nickNameLength > 16) throw new Exception("nickName Validate failed.",ErrorCode::Unknown);
        if (preg_match('/[^A-Za-z0-9]/',$nickName))throw new Exception("nickName Validate failed.",ErrorCode::Unknown);
        $bandedWordToUpper = [];
        foreach($bandedWord as $word){
            array_push($bandedWordToUpper,strtoupper($word));
        }
        $nickName = strtoupper($nickName);
        foreach($bandedWordToUpper as $word){
            if(preg_match('/'.$word.'/' ,$nickName))
            throw new Exception("nickName Validate failed.",ErrorCode::Unknown);
        }
    }
}