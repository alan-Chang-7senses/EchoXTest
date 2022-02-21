<?php

use Games\Exceptions\RaceException;

$lang[RaceException::UserInRace] = 'User in the race.';
$lang[RaceException::UserNotInRace] = 'User not in the race.';
$lang[RaceException::OverPlayerMax] = 'Incorrect number of players joined the match.';
$lang[RaceException::OtherUserInRace] = 'Have other user in the race. (User ID: [user])';

return $lang;