<?php

use Games\Exceptions\RaceException;

$lang[RaceException::UserInRace] = 'User in the race.';
$lang[RaceException::UserNotInRace] = 'User not in the race.';
$lang[RaceException::IncorrectPlayerNumber] = 'Incorrect number of players';
$lang[RaceException::OtherUserInRace] = 'Have other user in the race. (User ID: [user])';
$lang[RaceException::PlayerReached] = 'The player has reached the end.';
$lang[RaceException::Finished] = 'The race has finished.';
$lang[RaceException::PlayerNotReached] = 'The player not reach the end yet.(Player ID: [player])';
$lang[RaceException::RankingNoMatch] = 'The player ranking no match. (Player ID: [player], Ranking: [ranking], Player Count: [count])';
$lang[RaceException::FinishFailure] = 'The race execute finish process failure.';
$lang[RaceException::EnergyNotEnough] = 'The player current energy not enough.';
$lang[RaceException::PlayerNotInThisRace] = 'The player is not in this race. (Player ID: [player])';
$lang[RaceException::NotBotPlayer] = 'The player is not a bot';
$lang[RaceException::NotBotInMatch] = 'The bot player is not in match';
$lang[RaceException::EnergyNotRunOut] = 'The player current energy not exhausted.';
$lang[RaceException::EnergyAgainOver] = 'The player energy reacquired more than.';
$lang[RaceException::UserInMatch] = 'The player is matching.';
$lang[RaceException::UserNotInMatch] = 'The player is not in match.';
$lang[RaceException::UserMatchError] = 'There was an error in the matching process.';
$lang[RaceException::UserTicketNotEnough] = 'User have not enough tickets.';
$lang[RaceException::UserTicketUpperLimit] = 'User`s tickets exceed the upper limit.';
$lang[RaceException::UserTicketNotYet] = 'The time for the user to collect the ticket has not arrived yet.';
$lang[RaceException::NoSeasonData] = 'No season data.';
$lang[RaceException::UserTicketError] = 'Receive ticket has error.';
$lang[RaceException::UserInRoom] = 'User is in the room.';
$lang[RaceException::UserNotInRoom] = 'User is not in the room.';
$lang[RaceException::UserCheat] = 'User is cheating.';
$lang[RaceException::EnergyRunOutBonusNotExist] = 'Energy Run Out bonus does not exist.';
$lang[RaceException::UsePlayerError] = 'using player error';
$lang[RaceException::NotAvailableRace] = 'The race is not available at the moment.';
return $lang;