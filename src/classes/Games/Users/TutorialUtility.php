<?php

namespace Games\Users;

use Games\Users\ItemUtility;
use Games\Users\UserBagHandler;
use Games\Consts\ItemValue;
use Games\Consts\Tutorial;
use Generators\ConfigGenerator;
use stdClass;


/**
 * Description of TutorialUtility
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */

class TutorialUtility {

    public static function IsTutorialFinish(int $curStep) : bool
    {
        return ($curStep <= Tutorial::TutorialFinish || Tutorial::TutorialStepMax <= $curStep); 
    }

    public static function GetStepReward(int $curStep) : array
    {
        $rewards = json_decode(ConfigGenerator::Instance()->TutorialRewards ?? '[]');

        $items = [];

        foreach ($rewards as $reward)
        {
            if ($reward->Step == $curStep)
            {
                $items[] = $reward;
            }
        }
        return $items;
    }

    public static function UpdateStep(int $curStep): stdClass 
    {
        $result = new stdClass();
        $result->nextStep = Tutorial::TutorialFinish;
        $result->rewardItems = [];

        if (TutorialUtility::IsTutorialFinish($curStep)) return $result;

        $items = TutorialUtility::GetStepReward($curStep);
        foreach($items as $item)
        {
            $result->rewardItems[] = ItemUtility::GetClientSimpleInfo($item->ItemID, $item->Amount);
        }    

        $curStep += 1;

        $result->nextStep = TutorialUtility::IsTutorialFinish($curStep) ? Tutorial::TutorialFinish : $curStep;        

        return $result;
    }    

    public static function AddRewards(int $userID, array $rewards): bool
    {
        $userBagHandler = new UserBagHandler($userID);

        $item = new stdClass();

        foreach ($rewards as $reward)
        {
            $item->ItemID = $reward->itemID;
            $item->Amount = $reward->amount;
            $userBagHandler->AddItems($item, ItemValue::CauseTutorial);
        }

        return true;
    } 
}