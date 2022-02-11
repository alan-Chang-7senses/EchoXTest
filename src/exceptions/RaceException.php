<?php

use Games\Exceptions\RaceException;

$lang[RaceException::UserInRace] = 'User in the race.';
$lang[RaceException::OverPlayerMax] = 'Incorrect number of players joined the match.';
$lang[RaceException::UserNotExist] = 'User [user] does not exist.';
$lang[RaceException::OtherUserInRace] = 'Have other user in the race. (User ID: [user])';

return $lang;