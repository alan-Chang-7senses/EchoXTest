<?php

namespace Processors\Player;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\Sessions;
use Games\Exceptions\UserException;
use Games\Pools\PlayerPool;
use Games\Pools\UserPool;
use Generators\DataGenerator;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\BaseProcessor;

class SetSkill extends BaseProcessor{
    
    public function Process(): ResultData {
        
        $playerID = InputHelper::post('playerID');
        
        $userPool = UserPool::Instance();
        $userInfo = $userPool->{$_SESSION[Sessions::UserID]};
        if(!in_array($playerID, $userInfo->players)) throw new UserException (UserException::NotHoldPlayer, ['[player]' => $playerID]);
        
        $skillsData = json_decode(InputHelper::postNotEmpty('skillsData'));
        DataGenerator::ExistProperties($skillsData[0], ['skillID', 'slot']);
        
        $skillSlots = [];
        foreach($skillsData as $data) $skillSlots[$data->skillID] = $data->slot;
        
        $mainAccessor = new PDOAccessor(EnvVar::DBMain);
        $mainAccessor->FromTable('PlayerSkill')->PrepareName('SetSkill');
        
        $playerPool = PlayerPool::Instance();
        $playerInfo = $playerPool->$playerID;
        foreach ($playerInfo->skills as $skill){
            
            $slot = $skillSlots[$skill->id] ?? 0;
            $mainAccessor->ClearCondition();
            $mainAccessor->WhereEqual('PlayerID', $playerID)->WhereEqual('SkillID', $skill->id)->Modify(['Slot' => $slot]);
        }
        
        $playerPool->Delete($playerID);
        
        $result = new ResultData(ErrorCode::Success);
        return $result;
    }
}