<?php

namespace Processors\EliteTest;

use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\Predefined;
use Games\Accessors\EliteTestAccessor;
use Games\Consts\RaceValue;
use Games\EliteTest\EliteTestValues;
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
        $winUsers = [];
        
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
            }
            
            $scores[$user->id] = $user->score;
            if($user->ranking == 1) $winUsers[] = $user->id;
        }
        
        $res['racePlayers'] = $accessor->AddRacePlayers($racePlayers);
        if(!empty($raceSkills)) $res['raceSkills'] = $accessor->AddRaceSkills($raceSkills);
        $res['rinishRace'] = $accessor->FinishRaceByRaceID($raceID, EliteTestValues::RaceFinsh);
        $res['finishUserRace'] = $accessor->FinishUserByUserRaceScore(RaceValue::NotInRace, $scores);
        $res['totalUserRace']['Finish'] = $accessor->IncreaseTotalUserRaceFinishByUserIDs(array_keys($scores));
        $res['totalUserRace']['Win'] = $accessor->IncreaseTotalUserRaceWinByUserIDs($winUsers);
        
        $result = new ResultData(ErrorCode::Success);
        if(getenv(EnvVar::ProcessTiming) == Predefined::ProcessTiming) $result->res = $res;
        
        return $result;
    }
}
