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
        foreach($free3Players as $player)
        {
            $i++;            
            $aliasCodes = [];
            // $skills = [];
            $player->dna = new PlayerDnaHolder();
            $player->dna->head = FreePetaUtility::GetRandomElementInArray($table)->HeadDNA;
            $player->dna->body = FreePetaUtility::GetRandomElementInArray($table)->BodyDNA;
            $player->dna->hand = FreePetaUtility::GetRandomElementInArray($table)->HandDNA;
            $player->dna->leg = FreePetaUtility::GetRandomElementInArray($table)->LegDNA;
            $player->dna->back = FreePetaUtility::GetRandomElementInArray($table)->BackDNA;
            $player->dna->hat = FreePetaUtility::GetRandomElementInArray($table)->HatDNA;

            unset($player->ID);
            $player->number = $i;
            $player->native = 0; //原生種(暫時數值)
            $player->source = NFTDNA::FreePetaSource;
            $player->StrengthLevel = NFTDNA::StrengthNormalC;
            $player->SkeletonType = NFTDNA::PetaSkeletonType;

            

            $aliasCodes[] = FreePetaUtility::GetPartSkill(PlayerUtility::PartCodeByDNA($player->dna->head),PlayerValue::Head,$skillPartTable);
            $aliasCodes[] = FreePetaUtility::GetPartSkill(PlayerUtility::PartCodeByDNA($player->dna->body),PlayerValue::Body,$skillPartTable);
            $aliasCodes[] = FreePetaUtility::GetPartSkill(PlayerUtility::PartCodeByDNA($player->dna->hand),PlayerValue::Hand,$skillPartTable);
            $aliasCodes[] = FreePetaUtility::GetPartSkill(PlayerUtility::PartCodeByDNA($player->dna->leg),PlayerValue::Leg,$skillPartTable);
            $aliasCodes[] = FreePetaUtility::GetPartSkill(PlayerUtility::PartCodeByDNA($player->dna->back),PlayerValue::Back,$skillPartTable);
            $aliasCodes[] = FreePetaUtility::GetPartSkill(PlayerUtility::PartCodeByDNA($player->dna->hat),PlayerValue::Hat,$skillPartTable);
            $sa = new SkillAccessor();            

            $skillrows = $sa->rowsInfoByAliasCodes($aliasCodes);
            $player = new stdClass();
            $player->number = $i;
            $player->type = $player->Type;
            $player->ele = $player->Attribute;
            $player->velocity = PlayerAbility::Velocity($player->Agility, $player->Strength,1);
            $player->stamina = PlayerAbility::Stamina($player->Constitution, $player->Dexterity,1);
            $player->intelligent = PlayerAbility::Intelligent($player->Dexterity, $player->Agility, 1);
            $player->breakOut = PlayerAbility::BreakOut($player->Strength, $player->Dexterity, 1);
            $player->will = PlayerAbility::Will($player->Constitution, $player->Strength, 1);
            $player->habit = PlayerAbility::Habit($player->Constitution, $player->Strength, $player->Dexterity, $player->Agility);
            $player->dna = $player->dna;
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
                $player->skills[] = ["id" => $info->id];
            }            
            $freePetaTemp[] = $player;
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

