<?php

namespace Processors\User\FreePlayer;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\Sessions;
use Games\Consts\FreePlayerValue;
use Games\Consts\NFTDNA;
use Games\FreePlayer\FreePlayerHandler;
use Games\Pools\FreePlayerPool;
use Games\Skills\SkillHandler;
use Games\Users\FreePeta\FreePetaUtility;
use Holders\ResultData;
use Processors\BaseProcessor;
use stdClass;

class Get3FreePlayer extends BaseProcessor
{
    public function Process(): ResultData
    {
        $userID = $_SESSION[Sessions::UserID];
        
        
        $freePlayerHandlers = [];
        for($i = 0; $i < FreePlayerValue::FreePlayerTypeCount; ++$i)
        {
            $freePlayerHandlers[] = new FreePlayerHandler($i + 1);
        }
        
        $players = [];//給前
        $freePlayersForDB = [];//給後

        foreach($freePlayerHandlers as $freePlayerhandler)
        {
            $player = new stdClass();
            $playerForDB = new stdClass();                        
            $info = $freePlayerhandler->GetInfo();


            $player->number = $info->number;
            $player->type = $info->type;
            $player->ele = $info->ele;
            $player->velocity = $info->velocity;
            $player->stamina = $info->stamina;
            $player->intelligent = $info->intelligent;
            $player->breakOut = $info->breakOut;
            $player->will = $info->will;
            $player->habit = $info->habit;
            $player->dna = $info->dna;

            $playerForDB->number = $info->number;
            $playerForDB->Type = $info->type;
            $playerForDB->Attribute = $info->ele;
            $playerForDB->Constitution = $info->freePlayerBase->Constitution;
            $playerForDB->Strength = $info->freePlayerBase->Strength;
            $playerForDB->Dexterity = $info->freePlayerBase->Dexterity;
            $playerForDB->Agility = $info->freePlayerBase->Agility;
            $playerForDB->dna = $info->dna;
            $playerForDB->native = NFTDNA::NativeNone; 
            $playerForDB->source = NFTDNA::FreePetaSource;
            $playerForDB->StrengthLevel = NFTDNA::StrengthNormalC;
            $playerForDB->SkeletonType = NFTDNA::PetaSkeletonType;

            foreach($info->skills as $row)
            {
                $handler = new SkillHandler($row->id);
                $handler->playerHandler = $freePlayerhandler;
                $skillInfo = $handler->GetInfo();                
                $player->skills[] = 
                [
                    "id" => $skillInfo->id,
                    "name" => $skillInfo->name,
                    "icon" => $skillInfo->icon,
                    "description" => $skillInfo->description,
                    "energy" => $skillInfo->energy,
                    "cooldowm" => $skillInfo->cooldown,
                    "duration" => $skillInfo->duration,
                    "ranks" => $skillInfo->ranks,
                    "maxDescription" => $skillInfo->maxDescription,
                    "maxCondition" => $skillInfo->maxCondition,
                    "maxConditionValue" => $skillInfo->maxConditionValue,
                    "effects" => $handler->GetEffects(),
                    "maxEffects" => $handler->GetMaxEffects(),
                ];
                $playerForDB->skills[] = ["id" => $skillInfo->id]; // 給後
            }
            $players[] = $player;
            $freePlayersForDB[] = $playerForDB;
            FreePlayerPool::Instance()->Delete($info->number);
        }            

        
        $pdo = new PDOAccessor(EnvVar::DBMain);        
        $pdo->FromTable("UserFreePeta")
            ->Add(["UserID" => $userID,"FreePetaInfo" => json_encode($freePlayersForDB)],true);
        
        $results = new ResultData(ErrorCode::Success);
        foreach($players as $player) FreePetaUtility::PartcodeAllDNA($player->dna);
        $results->players = $players;
        
        return $results;
    }



}

