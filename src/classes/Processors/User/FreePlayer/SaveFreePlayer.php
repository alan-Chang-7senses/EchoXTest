<?php
namespace Processors\User\FreePlayer;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\Sessions;
use Consts\SetUserNicknameValue;
use Games\Accessors\UserAccessor;
use Games\Consts\DirtyWordValue;
use Games\Consts\FreePlayerValue;
use Games\Consts\PlayerValue;
use Games\Exceptions\UserException;
use Games\Users\FreePeta\FreePetaUtility;
use Games\Users\NamingUtility;
use Games\Users\UserHandler;
use Helpers\InputHelper;
use Holders\ResultData;
use PDOException;
use Processors\BaseProcessor;

/**
 * Description of SaveFreePlayer
 * 
 * @author Liu Shu Ming <mingoliu@7senses.com>
 */
class SaveFreePlayer extends BaseProcessor {

    public function Process(): ResultData {

        $nickname = InputHelper::post("nickname");

        $userID = $_SESSION[Sessions::UserID];
        $userHandler = new UserHandler($userID);
        $userInfo = $userHandler->GetInfo();
        

        // 玩家取得 3 隻免費 NFT
        $pdo = new PDOAccessor(EnvVar::DBMain);
        $row = $pdo->FromTable("UserFreePeta")
            ->WhereEqual("UserID", $userInfo->id)
            ->Fetch();

        $freePetas = json_decode($row->FreePetaInfo);

        foreach($freePetas as $peta) {
            FreePetaUtility::AddNewFreePlayer($peta, $_SESSION[Sessions::UserID]);
        }


        // 玩家改名
        if(!NamingUtility::IsOnlyEnglishAndNumber($nickname)) {
            throw new UserException(UserException::UsernameNotEnglishOrNumber);        
        }

        if(NamingUtility::ValidateLength($nickname,SetUserNicknameValue::MaxLenght)) {
            throw new UserException(UserException::UsernameTooLong);
        }

        if(NamingUtility::HasDirtyWords($nickname,DirtyWordValue::BandWordName)) {
            throw new UserException(UserException::UsernameDirty);
        }
        
        $playerID = $userInfo->id * PlayerValue::freePetaPlayerIDMultiplier + FreePlayerValue::FreePlayerDefaultID;

        try {
            (new UserAccessor())->ModifyUserValuesByID($userInfo->id, ["Nickname" => $nickname, "Player" => $playerID]);
        }
        catch(PDOException $ex) {
            $info = $ex->errorInfo;   
            if(!empty($info) && $info[0] == ErrorCode::PDODuplicate)
            throw new UserException(UserException::UsernameAlreadyExist,['username' => $nickname]);
            else throw $ex;
        }
        
        $result = new ResultData(ErrorCode::Success);

        return $result;
    }
}