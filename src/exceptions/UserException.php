<?php

use Games\Exceptions\UserException;

$lang[UserException::UserNotExist] = 'User [user] does not exist.';
$lang[UserException::NotHoldPlayer] = 'User does not hold this player.(Player ID: [player])';

return $lang;