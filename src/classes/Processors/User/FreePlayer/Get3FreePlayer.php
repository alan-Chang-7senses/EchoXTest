<?php

namespace Processors\User\FreePlayer;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\Sessions;
use Consts\SetUserNicknameValue;
use Exception;
use Exceptions\NormalException;
use Games\Accessors\SkillAccessor;
use Games\Consts\NFTDNA;
use Games\Consts\PlayerValue;
use Games\Consts\SkillValue;
use Games\Exceptions\UserException;
use Games\Players\Adaptability\SlotNumber;
use Games\Players\Holders\PlayerDnaHolder;
use Games\Players\PlayerAbility;
use Games\Players\PlayerUtility;
use Games\Skills\SkillHandler;
use Games\Users\FreePeta\FreePetaUtility;
use Games\Users\UserHandler;
use Holders\ResultData;
use Processors\BaseProcessor;
use Processors\User\FreePlayer\Const\FreePetaValue;
use Processors\User\SetUserNickname;
use stdClass;

class Get3FreePlayer extends BaseProcessor
{
    public function Process(): ResultData
    {
        $userID = $_SESSION[Sessions::UserID];
        $userHandler = new UserHandler($userID);
        $userInfo = $userHandler->GetInfo();
        
        // if(!empty($userInfo->nickname))
        // throw new UserException(UserException::AlreadyHadFreePeta,["user" => $userInfo->id]);

        //分類所有免費peta
        $pdo = new PDOAccessor(EnvVar::DBStatic);
        $table = $pdo->FromTable("FreePetaInfo")->FetchAll();
        $freePlayers = array();
        foreach ($table as $row) 
        {
            $freePlayers[$row->Type][] = $row;
        }
        //選出三隻屬性不一樣的
        $free3Players = [];
        for($i = 0; $i < FreePetaValue::FreePetaTypeCount; ++$i)
        {
            $free3Players[] = FreePetaUtility::GetRandomElementInArray($freePlayers[$i]);
            // $free3Players[$i]->dnaHolder = new PlayerDnaHolder();
        }
        $pdo->ClearAll();
        $table = $pdo->FromTable("FreePetaDNA")->FetchAll();
        
        $pdo->ClearAll();
        $skillPartTable = $pdo->FromTable("SkillPart")
                              ->FetchAll();
        $players = [];
        $freePetaTemp = []; // DB紀錄用

        $i = 0;                              
        foreach($free3Players as $freePlayer)
        {
            $i++;            
            $aliasCodes = [];
            // $skills = [];
            $freePlayer->dna = new PlayerDnaHolder();
            $freePlayer->dna->head = FreePetaUtility::GetRandomElementInArray($table)->HeadDNA;
            $freePlayer->dna->body = FreePetaUtility::GetRandomElementInArray($table)->BodyDNA;
            $freePlayer->dna->hand = FreePetaUtility::GetRandomElementInArray($table)->HandDNA;
            $freePlayer->dna->leg = FreePetaUtility::GetRandomElementInArray($table)->LegDNA;
            $freePlayer->dna->back = FreePetaUtility::GetRandomElementInArray($table)->BackDNA;
            $freePlayer->dna->hat = FreePetaUtility::GetRandomElementInArray($table)->HatDNA;

            unset($freePlayer->ID);
            $freePlayer->number = $i;
            $freePlayer->native = 0; //原生種(暫時數值)
            $freePlayer->source = NFTDNA::FreePetaSource;
            $freePlayer->StrengthLevel = NFTDNA::StrengthNormalC;
            $freePlayer->SkeletonType = NFTDNA::PetaSkeletonType;

            

            $aliasCodes[] = FreePetaUtility::GetPartSkill(PlayerUtility::PartCodeByDNA($freePlayer->dna->head),PlayerValue::Head,$skillPartTable);
            $aliasCodes[] = FreePetaUtility::GetPartSkill(PlayerUtility::PartCodeByDNA($freePlayer->dna->body),PlayerValue::Body,$skillPartTable);
            $aliasCodes[] = FreePetaUtility::GetPartSkill(PlayerUtility::PartCodeByDNA($freePlayer->dna->hand),PlayerValue::Hand,$skillPartTable);
            $aliasCodes[] = FreePetaUtility::GetPartSkill(PlayerUtility::PartCodeByDNA($freePlayer->dna->leg),PlayerValue::Leg,$skillPartTable);
            $aliasCodes[] = FreePetaUtility::GetPartSkill(PlayerUtility::PartCodeByDNA($freePlayer->dna->back),PlayerValue::Back,$skillPartTable);
            $aliasCodes[] = FreePetaUtility::GetPartSkill(PlayerUtility::PartCodeByDNA($freePlayer->dna->hat),PlayerValue::Hat,$skillPartTable);
            $sa = new SkillAccessor();            

            $skillrows = $sa->rowsInfoByAliasCodes($aliasCodes);
            $player = new stdClass();
            $player->number = $i;
            $player->type = $freePlayer->Type;
            $player->ele = $freePlayer->Attribute;
            $player->velocity = PlayerAbility::Velocity($freePlayer->Agility, $freePlayer->Strength,1);
            $player->stamina = PlayerAbility::Stamina($freePlayer->Constitution, $freePlayer->Dexterity,1);
            $player->intelligent = PlayerAbility::Intelligent($freePlayer->Dexterity, $freePlayer->Agility, 1);
            $player->breakOut = PlayerAbility::BreakOut($freePlayer->Strength, $freePlayer->Dexterity, 1);
            $player->will = PlayerAbility::Will($freePlayer->Constitution, $freePlayer->Strength, 1);
            $player->habit = PlayerAbility::Habit($freePlayer->Constitution, $freePlayer->Strength, $freePlayer->Dexterity, $freePlayer->Agility);
            $player->dna = $freePlayer->dna;
            foreach($skillrows as $row)
            {
                $handler = new SkillHandler($row->SkillID);
                $info = $handler->GetInfo();                
                $player->skills[] = 
                [
                    "id" => $info->id,
                    "name" => $info->name,
                    "description" => $info->description,
                    "energy" => $info->energy,
                    "cooldowm" => $info->cooldown,
                    "duration" => $info->duration,
                    "ranks" => $info->ranks,
                    "maxDescription" => $info->maxDescription,
                    "maxCondition" => $info->maxCondition,
                    "maxConditionValue" => $info->maxConditionValue,
                    "effects" => $handler->GetEffects(),
                    "maxEffects" => $handler->GetMaxEffects(),
                ];
                $freePlayer->skills[] = ["id" => $info->id];
            }            
            $freePetaTemp[] = $freePlayer;
            $players[] = $player;
        }

        
        $pdo = new PDOAccessor(EnvVar::DBMain);        
        $pdo->FromTable("UserFreePeta")
            ->Add(["UserID" => $userID,"FreePetaInfo" => json_encode($freePetaTemp)],true);
        
        $results = new ResultData(ErrorCode::Success);
        foreach($players as $player) FreePetaUtility::PartcodeAllDNA($player->dna);
        $results->players = $players;
        return $results;
    }



}

