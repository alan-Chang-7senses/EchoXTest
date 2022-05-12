<?php

use Games\Exceptions\RaceException;

$lang[RaceException::UserInRace] = 'User in the race.';
$lang[RaceException::UserNotInRace] = 'User not in the race.';
$lang[RaceException::IncorrectPlayerNumber] = 'Incorrect number of players';
$lang[RaceException::OtherUserInRace] = 'Have other user in the race. (User ID: [user])';
$lang[RaceException::PlayerReached] = 'The player has reached the end.';
$lang[RaceException::Finished] = 'The race has finished.';
$lang[RaceException::PlayerNotReached] = 'The player not reach the end yet.(Player ID: [player])';
$lang[RaceException::RankingNoMatch] = 'The player ranking no match. (Player ID: [player], Ranking: [front] => [back])';
$lang[RaceException::FinishFailure] = 'The race execute finish process failure.';
$lang[RaceException::EnergyNotEnough] = 'The player\'s current energy not enough.';
$lang[RaceException::PlayerNotInThisRace] = 'The player is not in this race.';
$lang[RaceException::NotBotPlayer] = 'The player is not a bot';
$lang[RaceException::NotBotInMatch] = 'The bot player is not in match';

return $lang;