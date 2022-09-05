<?php

use Games\Exceptions\PlayerException;

$lang[PlayerException::PlayerNotExist] = 'Player [player] does not exist.';
$lang[PlayerException::NoSuchSkill] = 'Player [player] no such skill id:[skillID]';
$lang[PlayerException::NicknameInValid] = 'Nickname [nickname] is invalid';
$lang[PlayerException::NicknameLengthError] = 'Nickname length is invalid';
$lang[PlayerException::NicknameNotEnglish] = 'Nickname not English or number';
$lang[PlayerException::AlreadyRankMax] = 'Player: [playerID] has already rank max.';
$lang[PlayerException::NotReachMaxLevelYet] = 'Player: [playerID] has not reach level max yet.';

return $lang;