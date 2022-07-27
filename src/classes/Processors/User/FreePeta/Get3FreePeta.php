<?php

namespace Processors\User\FreePeta;

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
use Processors\User\FreePeta\Const\FreePetaValue;
use Processors\User\SetUserNickname;
use stdClass;

class Get3FreePeta extends BaseProcessor
{
    public function Process(): ResultData
    {
        $userID = $_SESSION[Sessions::UserID];
        $userHandler = new UserHandler($userID);
        $userInfo = $userHandler->GetInfo();
        
        if(!empty($userInfo->nickname))
        throw new UserException(UserException::AlreadyHadFreePeta,["user" => $userInfo->id]);

        //分類所有免費peta
        $pdo = new PDOAccessor(EnvVar::DBStatic);
        $table = $pdo->FromTable("FreePetaInfo")->FetchAll();
        $freePetas = array();
        foreach ($table as $row) 
        {
            $freePetas[$row->Type][] = $row;
        }
        //選出三隻屬性不一樣的
        $free3Peta = [];
        for($i = 0; $i < FreePetaValue::FreePetaTypeCount; ++$i)
        {
            $free3Peta[] = FreePetaUtility::GetRandomElementInArray($freePetas[$i]);
            // $free3Peta[$i]->dnaHolder = new PlayerDnaHolder();
        }
        $pdo->ClearAll();
        $table = $pdo->FromTable("FreePetaDNA")->FetchAll();
        
        $pdo->ClearAll();
        $skillPartTable = $pdo->FromTable("SkillPart")
                              ->FetchAll();
        $players = [];
        $freePetaTemp = []; // DB紀錄用

        $i = 0;                              
        foreach($free3Peta as $peta)
        {
            $i++;            
            $aliasCodes = [];
            // $skills = [];
            $peta->dna = new PlayerDnaHolder();
            $peta->dna->head = FreePetaUtility::GetRandomElementInArray($table)->HeadDNA;
            $peta->dna->body = FreePetaUtility::GetRandomElementInArray($table)->BodyDNA;
            $peta->dna->hand = FreePetaUtility::GetRandomElementInArray($table)->HandDNA;
            $peta->dna->leg = FreePetaUtility::GetRandomElementInArray($table)->LegDNA;
            $peta->dna->back = FreePetaUtility::GetRandomElementInArray($table)->BackDNA;
            $peta->dna->hat = FreePetaUtility::GetRandomElementInArray($table)->HatDNA;

            unset($peta->ID);
            $peta->number = $i;
            $peta->native = 0; //原生種(暫時數值)
            $peta->source = PlayerValue::FreePetaSource;
            $peta->StrengthLevel = NFTDNA::StrengthNormalC;
            $peta->SkeletonType = PlayerValue::PetaSkeletonType;

            

            $aliasCodes[] = FreePetaUtility::GetPartSkill(PlayerUtility::PartCodeByDNA($peta->dna->head),PlayerValue::Head,$skillPartTable);
            $aliasCodes[] = FreePetaUtility::GetPartSkill(PlayerUtility::PartCodeByDNA($peta->dna->body),PlayerValue::Body,$skillPartTable);
            $aliasCodes[] = FreePetaUtility::GetPartSkill(PlayerUtility::PartCodeByDNA($peta->dna->hand),PlayerValue::Hand,$skillPartTable);
            $aliasCodes[] = FreePetaUtility::GetPartSkill(PlayerUtility::PartCodeByDNA($peta->dna->leg),PlayerValue::Leg,$skillPartTable);
            $aliasCodes[] = FreePetaUtility::GetPartSkill(PlayerUtility::PartCodeByDNA($peta->dna->back),PlayerValue::Back,$skillPartTable);
            $aliasCodes[] = FreePetaUtility::GetPartSkill(PlayerUtility::PartCodeByDNA($peta->dna->hat),PlayerValue::Hat,$skillPartTable);
            $sa = new SkillAccessor();            

            $skillrows = $sa->rowsInfoByAliasCodes($aliasCodes);
            $player = new stdClass();
            $player->number = $i;
            $player->type = $peta->Type;
            $player->ele = $peta->Attribute;
            $player->velocity = PlayerAbility::Velocity($peta->Agility, $peta->Strength,1);
            $player->stamina = PlayerAbility::Stamina($peta->Constitution, $peta->Dexterity,1);
            $player->intelligent = PlayerAbility::Intelligent($peta->Dexterity, $peta->Agility, 1);
            $player->breakOut = PlayerAbility::BreakOut($peta->Strength, $peta->Dexterity, 1);
            $player->will = PlayerAbility::Will($peta->Constitution, $peta->Strength, 1);
            $player->habit = PlayerAbility::Habit($peta->Constitution, $peta->Strength, $peta->Dexterity, $peta->Agility);
            $player->dna = $peta->dna;
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
                $peta->skills[] = ["id" => $info->id];
            }            
            $freePetaTemp[] = $peta;
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

