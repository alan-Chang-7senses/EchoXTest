<?php

namespace Processors\EliteTest;

use Consts\ErrorCode;
use Games\Accessors\EliteTestAccessor;
use Games\Consts\RaceValue;
use Games\Exceptions\RaceException;
use Generators\ConfigGenerator;
use Generators\DataGenerator;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\BaseProcessor;
/**
 * Description of RaceFinish
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class RaceFinish extends BaseProcessor{
    
    public function Process(): ResultData {
        
        $raceID = InputHelper::post('raceID');
        $users = json_decode(InputHelper::post('users'));
        if(!is_array($users) || count($users) > ConfigGenerator::Instance()->AmountRacePlayerMax) throw new RaceException(RaceException::IncorrectPlayerNumber);
        DataGenerator::ExistProperties($users[0], [
            'id', 'score', 'trackOrder', 'ranking', 'duration', 'finishS', 'finishH', 'skills'
        ]);
        
        $accessor = new EliteTestAccessor();
        $racePlayers = [];
        $raceSkills = [];
        $scores = [];
        $totalSkills = [];
        
        foreach($users as $user){
            
            $racePlayers[] = [
                'RaceID' => $raceID,
                'UserID' => $user->id,
                'TrackOrder' => $user->trackOrder,
                'Ranking' => $user->ranking,
                'Duration' => $user->duration,
                'FinishS' => $user->finishS,
                'FinishH' => $user->finishH,
            ];
            
            foreach($user->skills as $skill){
                
                $raceSkills[] = [
                    'RaceID' => $raceID,
                    'UserID' => $user->id,
                    'SkillID' => $skill->id,
                    'Position' => $skill->position,
                    'TrackType' => $skill->trackType,
                    'TrackShape' => $skill->trackShape,
                    'BeforeS' => $skill->beforeS,
                    'BeforeH' => $skill->beforeH,
                    'BeforeEnergy' => $skill->beforeEnergy,
                    'AfterS' => $skill->afterS,
                    'AfterH' => $skill->afterH,
                    'AfterEnergy' => $skill->afterEnergy,
                ];
                
                if(!isset($totalSkills[$skill->id])) $totalSkills[$skill->id] = 0;
                $totalSkills[$skill->id]++;
            }
            
            $scores[$user->id] = $user->score;
        }
        
        $res['racePlayers'] = $accessor->AddRacePlayers($racePlayers);
        $res['raceSkills'] = $accessor->AddRaceSkills($raceSkills);
        $res['rinishRace'] = $accessor->FinishRaceByRaceID($raceID, RaceValue::StatusFinish);
        $res['finishUserRace'] = $accessor->FinishUserByUserRaceScore(RaceValue::NotInRace, $scores);
        $res['totalSkills'] = $accessor->IncreaseTotalSkills($totalSkills);
        $res['totalUserRace'] = $accessor->IncreaseTotalUserRaceFinishByUserIDs(array_keys($scores));
        
        $result = new ResultData(ErrorCode::Success);
        if(ConfigGenerator::Instance()->EnabledProcessTime == 1) $result->res = $res;
        
        return $result;
    }
}
