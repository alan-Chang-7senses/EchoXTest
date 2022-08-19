<?php

namespace Processors\Player;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Holders\ResultData;
use Consts\ErrorCode;
use Consts\Sessions;
use Games\Consts\DirtyWordValue;
use Games\Consts\PlayerValue;
use Games\Exceptions\PlayerException;
use Processors\BaseProcessor;
use Games\Users\UserHandler;
use Helpers\InputHelper;
use Games\Exceptions\UserException;
use Games\Pools\PlayerPool;
use Games\Users\NamingUtility;

class SetNickname extends BaseProcessor{

    public function Process(): ResultData
    {
        $playerID = InputHelper::post('id');
        $nickName = InputHelper::post('nickname');
        
        if(!NamingUtility::IsOnlyEnglishAndNumber($nickName))
        throw new PlayerException(PlayerException::NicknameNotEnglish);
        if(NamingUtility::ValidateLength($nickName,PlayerValue::PlayerNicknameMaxLength))
        throw new PlayerException(PlayerException::NicknameLengthError);
        if(NamingUtility::HasDirtyWords($nickName,DirtyWordValue::BandWordName))
        throw new PlayerException(PlayerException::NicknameInValid, ['nickname' => $nickName]);
        
        $userHandler = new UserHandler($_SESSION[Sessions::UserID]);
        $userInfo = $userHandler->GetInfo();

        if(!in_array($playerID, $userInfo->players)) throw new UserException(UserException::NotHoldPlayer, ['[player]' => $playerID]);
        
     
        $pdo = new PDOAccessor(EnvVar::DBMain);
        $pdo->FromTable('PlayerHolder')
        ->WhereEqual('UserID',$userInfo->id)
        ->WhereEqual('PlayerID',$playerID)
        ->Modify(['Nickname' => $nickName]);
        
        $playerPool = PlayerPool::Instance();
        $playerPool->Delete($playerID);

        $results = new ResultData(ErrorCode::Success);
        return $results;
    }
}