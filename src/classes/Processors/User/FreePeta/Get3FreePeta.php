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
        throw new UserException(UserException::AlreadyHadFreePeta);

        $pdo = new PDOAccessor(EnvVar::DBStatic);
        $table = $pdo->FromTable("FreePetaInfo")->FetchAll();
        $freePetas = array();
        foreach ($table as $row) 
        {
            $freePetas[$row->Type][] = $row;
        }
        $free3Peta = [];
        for($i = 0; $i < FreePetaValue::FreePetaTypeCount; ++$i)
        {
            $free3Peta[] = Get3FreePeta::GetRandomElementInArray($freePetas[$i]);
            // $free3Peta[$i]->dnaHolder = new PlayerDnaHolder();
        }
        //隨機組合DNA
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
            //TODO：從表中可隨機的DNA去隨機一組六個部位的DNA，根據公式算出數值，生成六個技能。
            $aliasCodes = [];
            // $skills = [];
            $peta->dna = new PlayerDnaHolder();
            $peta->dna->head = Get3FreePeta::GetRandomElementInArray($table)->HeadDNA;
            $peta->dna->body = Get3FreePeta::GetRandomElementInArray($table)->BodyDNA;
            $peta->dna->hand = Get3FreePeta::GetRandomElementInArray($table)->HandDNA;
            $peta->dna->leg = Get3FreePeta::GetRandomElementInArray($table)->LegDNA;
            $peta->dna->back = Get3FreePeta::GetRandomElementInArray($table)->BackDNA;
            $peta->dna->hat = Get3FreePeta::GetRandomElementInArray($table)->HatDNA;

            unset($peta->ID);
            $peta->number = $i;
            $peta->source = 0;
            $peta->StrengthLevel = 8;
            $peta->SkeletonType = 00;

            $freePetaTemp[] = $peta;

            $aliasCodes[] = Get3FreePeta::GetPartSkill(PlayerUtility::PartCodeByDNA($peta->dna->head),PlayerValue::Head,$skillPartTable);
            $aliasCodes[] = Get3FreePeta::GetPartSkill(PlayerUtility::PartCodeByDNA($peta->dna->body),PlayerValue::Body,$skillPartTable);
            $aliasCodes[] = Get3FreePeta::GetPartSkill(PlayerUtility::PartCodeByDNA($peta->dna->hand),PlayerValue::Hand,$skillPartTable);
            $aliasCodes[] = Get3FreePeta::GetPartSkill(PlayerUtility::PartCodeByDNA($peta->dna->leg),PlayerValue::Leg,$skillPartTable);
            $aliasCodes[] = Get3FreePeta::GetPartSkill(PlayerUtility::PartCodeByDNA($peta->dna->back),PlayerValue::Back,$skillPartTable);
            $aliasCodes[] = Get3FreePeta::GetPartSkill(PlayerUtility::PartCodeByDNA($peta->dna->hat),PlayerValue::Hat,$skillPartTable);
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
                    "maxConditionValue" => $info->maxConditionValue,
                    "effects" => $handler->GetEffects(),
                    "maxEffects" => $handler->GetMaxEffects(),
                ];
            }            
            $players[] = $player;
        }

        

        //TODO：將生成的免費Peta轉Json放進DB
        $pdo = new PDOAccessor(EnvVar::DBMain);        
        $pdo->FromTable("UserFreePeta")
            ->Add(["UserID" => $userID,"FreePetaInfo" => json_encode($freePetaTemp)],true);
        
        //包給前端
        $results = new ResultData(ErrorCode::Success);
        foreach($players as $player) Get3FreePeta::PartcodeAllDNA($player->dna);
        $results->players = $players;
        return $results;
    }


    public static function GetRandomElementInArray(array $a)
    {
        return $a[rand(0,count($a) - 1)];
    }

    public static function GetPartSkill(string $partCode,int $partType, $skillPartTable) : string 
    {        
        foreach($skillPartTable as $row)
        {            
            if($row->PartCode == $partCode && $row->PartType == $partType)
            {
                $aliasCode = $row->AliasCode1;
            }
        }
        return empty($aliasCode) ? false : $aliasCode;
    }

    public static function PartcodeAllDNA(PlayerDnaHolder $dna)
    {
        $dna->head = PlayerUtility::PartCodeByDNA($dna->head);
        $dna->body = PlayerUtility::PartCodeByDNA($dna->body);
        $dna->hand = PlayerUtility::PartCodeByDNA($dna->hand);
        $dna->leg = PlayerUtility::PartCodeByDNA($dna->leg);
        $dna->back = PlayerUtility::PartCodeByDNA($dna->back);
        $dna->hat = PlayerUtility::PartCodeByDNA($dna->hat);
    }

}

