<?php

use Games\Exceptions\PlayerException;

$lang[PlayerException::PlayerNotExist] = 'Player [player] does not exist.';
$lang[PlayerException::NoSuchSkill] = 'Player [player] no this skill id:[skillID]';
$lang[PlayerException::NoEquipSkill] = 'Player [player] no equip this skill [skillID]';
$lang[PlayerException::NicknameInValid] = 'Nickname [nickname] is invalid';
$lang[PlayerException::NicknameLengthError] = 'Nickname length is invalid';
$lang[PlayerException::NicknameNotEnglish] = 'Nickname not English or number';
$lang[PlayerException::AlreadyRankMax] = 'Player: [playerID] has already rank max.';
$lang[PlayerException::NotReachMaxLevelYet] = 'Player: [playerID] has not reach level max yet.';
$lang[PlayerException::SkillLevelMax] = 'Player: [playerID] skill [skillID] reached level max.';


return $lang;