<?php

namespace Processors\Player;

use Consts\ErrorCode;
use Consts\Sessions;
use Games\Consts\UpgradeValue;
use Games\Exceptions\UserException;
use Games\Players\Exp\PlayerEXP;
use Games\Players\PlayerAbility;
use Games\Players\PlayerHandler;
use Games\Users\UserBagHandler;
use Games\Users\UserHandler;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\BaseProcessor;

class UpgradePage extends BaseProcessor{

    const CoinCostNone = 0;
    public function Process(): ResultData
    {

        $playerID = InputHelper::post('playerID');
        $itemInfo = json_decode(InputHelper::post('item'));
        $userID = $_SESSION[Sessions::UserID];
        $userPlayers = (new UserHandler($userID))->GetInfo()->players;
        $userBagHandler = new UserBagHandler($userID);

        if(!in_array($playerID,$userPlayers))
        throw new UserException(UserException::NotHoldPlayer,['player' => $playerID]);
        
        $results = new ResultData(ErrorCode::Success);
        $playerHandler = new PlayerHandler($playerID);
        $playerInfo = $playerHandler->GetInfo();
        $results->currentLevel = $playerInfo->level;
        $results->rank = $playerInfo->rank;
        $results->currentValue = 
        [
            'velocity' => $playerInfo->velocity,
            'stamina' => $playerInfo->stamina,
            'breakOut' => $playerInfo->breakOut,
            'will' => $playerInfo->will,
            'intelligent' => $playerInfo->intelligent,
        ];            
        if(empty($itemInfo))
        {
            $results->upgradeLevel = $playerInfo->level + PlayerEXP::LevelUnit;
            $values = PlayerAbility::GetAbilityValueByLevel($playerID,$playerInfo->level + PlayerEXP::LevelUnit,$playerInfo->strengthLevel);
            $results->upgradeValue = (array)$values;
            $upgradeItems = 
            [
                UpgradeValue::UpgradeItemIDSmall => $userBagHandler->GetItemAmount(UpgradeValue::UpgradeItemIDSmall),
                UpgradeValue::UpgradeItemIDMiddle => $userBagHandler->GetItemAmount(UpgradeValue::UpgradeItemIDMiddle),
                UpgradeValue::UpgradeItemIDLarge => $userBagHandler->GetItemAmount(UpgradeValue::UpgradeItemIDLarge),
            ];
            $results->upgradeItems = $upgradeItems;
            $results->cost = self::CoinCostNone;
        }

        return $results;
    }
}